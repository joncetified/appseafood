<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivationMail;
use App\Models\AccountActivationToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountActivationController extends Controller
{
    public function notice(Request $request): View
    {
        return view('auth.activation-sent', [
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function activate(string $token): RedirectResponse
    {
        $activationToken = AccountActivationToken::query()
            ->with('user')
            ->where('token', hash('sha256', $token))
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (! $activationToken || ! $activationToken->user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Link aktivasi tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $activationToken->user->forceFill([
            'is_active' => true,
            'email_verified_at' => now(),
        ])->save();

        AccountActivationToken::query()
            ->where('user_id', $activationToken->user_id)
            ->delete();

        return redirect()->route('login')->with('status', 'Akun berhasil diaktivasi. Silakan login.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->where('is_active', false)
            ->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Tidak ada akun nonaktif dengan email tersebut.',
            ]);
        }

        $this->sendActivationEmail($user);

        return redirect()
            ->route('activation.notice', ['email' => $user->email])
            ->with('status', 'Link aktivasi baru sudah dikirim.');
    }

    public function sendActivationEmail(User $user): void
    {
        AccountActivationToken::query()->where('user_id', $user->id)->delete();

        $rawToken = Str::random(64);

        AccountActivationToken::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => hash('sha256', $rawToken),
            'expires_at' => now()->addHours(24),
        ]);

        Mail::to($user->email)->send(new AccountActivationMail(
            $user,
            route('activation.verify', $rawToken),
        ));
    }
}
