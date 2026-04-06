<?php

namespace App\Services;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CaptchaService
{
    public function payload(Request $request): array
    {
        $question = $this->regenerateMathQuestion($request);

        return [
            'provider' => $this->useGoogleCaptcha() ? 'google' : 'math',
            'google_site_key' => config('services.recaptcha.site_key'),
            'math_question' => $question['question'],
        ];
    }

    public function validate(Request $request): void
    {
        if (app()->environment('testing')) {
            return;
        }

        if ($this->useGoogleCaptcha()) {
            if ($this->verifyGoogleCaptcha($request)) {
                return;
            }

            throw ValidationException::withMessages([
                'captcha' => 'Captcha Google gagal diverifikasi.',
            ]);
        }

        if ($this->verifyMathCaptcha($request)) {
            return;
        }

        throw ValidationException::withMessages([
            'captcha' => 'Math captcha tidak valid.',
        ]);
    }

    private function useGoogleCaptcha(): bool
    {
        $profile = CompanyProfile::query()->first();
        $mode = $profile?->captcha_mode ?? 'auto';

        if ($mode === 'math') {
            return false;
        }

        if (! config('services.recaptcha.site_key') || ! config('services.recaptcha.secret_key')) {
            return false;
        }

        return in_array($mode, ['auto', 'google'], true);
    }

    private function verifyGoogleCaptcha(Request $request): bool
    {
        $token = $request->input('g-recaptcha-response');

        if (! $token || ! $this->useGoogleCaptcha()) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            return (bool) data_get($response->json(), 'success', false);
        } catch (\Throwable) {
            return false;
        }
    }

    private function verifyMathCaptcha(Request $request): bool
    {
        $expected = $request->session()->get('math_captcha.answer');
        $answer = trim((string) $request->input('math_answer'));

        if ($expected === null || $answer === '') {
            return false;
        }

        return hash_equals((string) $expected, $answer);
    }

    private function regenerateMathQuestion(Request $request): array
    {
        $left = random_int(1, 9);
        $right = random_int(1, 9);

        $request->session()->put('math_captcha', [
            'question' => "{$left} + {$right}",
            'answer' => (string) ($left + $right),
        ]);

        return $request->session()->get('math_captcha');
    }
}
