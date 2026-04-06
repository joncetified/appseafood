<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\DiscordWebhookService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showRequestForm(Request $request, CaptchaService $captchaService): View
    {
        return view('auth.passwords.request', [
            'captcha' => $captchaService->payload($request),
            'supportWhatsapp' => CompanyProfile::query()->value('whatsapp'),
        ]);
    }

    public function sendResetLink(
        Request $request,
        CaptchaService $captchaService,
        DiscordWebhookService $discordWebhookService,
    ): View|RedirectResponse {
        $validated = $request->validate([
            'delivery_method' => ['required', 'in:email,whatsapp'],
            'email' => ['required', 'email'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
        ]);

        $captchaService->validate($request);

        $userQuery = User::query()->where('email', $validated['email']);

        if ($validated['delivery_method'] === 'whatsapp') {
            $request->validate([
                'whatsapp_number' => ['required', 'string', 'max:255'],
            ]);

            $userQuery->where('whatsapp_number', $validated['whatsapp_number']);
        }

        $user = $userQuery->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Akun dengan data tersebut tidak ditemukan.',
            ])->withInput();
        }

        $rawToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => hash('sha256', $rawToken),
                'created_at' => now(),
            ],
        );

        $resetUrl = route('password.reset.form', [
            'token' => $rawToken,
            'email' => $user->email,
        ]);

        if ($validated['delivery_method'] === 'email') {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

            return redirect()->route('login')->with('status', 'Link reset password sudah dikirim ke email.');
        }

        $supportWhatsapp = CompanyProfile::query()->value('whatsapp');

        if (! $supportWhatsapp) {
            return back()->withErrors([
                'whatsapp_number' => 'WhatsApp support belum diatur di website settings.',
            ])->withInput();
        }

        $message = rawurlencode(
            "Halo admin, saya minta reset password untuk {$user->email}. ".
            "Tolong kirim link ini ke saya: {$resetUrl}"
        );

        $discordWebhookService->sendMessage(
            'Permintaan Reset Password WhatsApp',
            'Permintaan reset password baru dikirim melalui jalur WhatsApp.',
            [
                ['name' => 'User', 'value' => $user->auditLabel(), 'inline' => false],
                ['name' => 'Requested At', 'value' => now()->format('d/m/Y H:i:s'), 'inline' => true],
            ],
        );

        return view('auth.passwords.whatsapp-request', [
            'supportWhatsapp' => preg_replace('/\D+/', '', $supportWhatsapp),
            'whatsappUrl' => 'https://wa.me/'.preg_replace('/\D+/', '', $supportWhatsapp).'?text='.$message,
            'email' => $user->email,
        ]);
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (! $record) {
            return back()->withErrors([
                'email' => 'Token reset password tidak ditemukan.',
            ])->withInput();
        }

        $expiresAt = Carbon::parse($record->created_at)->addHour();

        if ($expiresAt->isPast() || ! hash_equals($record->token, hash('sha256', $validated['token']))) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            ])->withInput();
        }

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'User tidak ditemukan.',
            ])->withInput();
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
    }
}
