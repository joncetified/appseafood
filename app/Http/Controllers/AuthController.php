<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\CaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AccountActivationController $activationController,
    ) {
    }

    public function showLogin(Request $request, CaptchaService $captchaService): View
    {
        return view('auth.login', [
            'captcha' => $captchaService->payload($request),
        ]);
    }

    public function login(Request $request, CaptchaService $captchaService): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $captchaService->validate($request);

        $user = User::query()
            ->with('role')
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Username/email atau password salah.',
            ])->onlyInput('email');
        }

        if (! $user->is_active) {
            return back()->withErrors([
                'email' => 'Akun belum aktif. Cek email untuk aktivasi dulu.',
            ])->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Gagal login. Coba lagi.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $redirectTo = $request->user()?->canAccessPage('dashboard')
            ? route('admin.dashboard')
            : route('home');

        return redirect()->intended($redirectTo);
    }

    public function showRegister(Request $request, CaptchaService $captchaService): View
    {
        return view('auth.register', [
            'captcha' => $captchaService->payload($request),
        ]);
    }

    public function register(Request $request, CaptchaService $captchaService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $captchaService->validate($request);

        $pelangganRole = Role::where('name', 'pelanggan')->first();

        $user = User::create([
            'role_id' => $pelangganRole?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'is_active' => false,
            'password' => $validated['password'],
        ]);

        $this->activationController->sendActivationEmail($user);

        return redirect()
            ->route('activation.notice', ['email' => $user->email])
            ->with('status', 'Akun dibuat. Aktivasi dulu lewat link email yang kami kirim.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
