<div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
    <p class="text-sm font-semibold text-slate-800">Captcha</p>
    <p class="mt-1 text-xs text-slate-500">
        {{ ($captcha['provider'] ?? 'math') === 'google' ? 'Mode online aktif: menggunakan Google reCAPTCHA.' : 'Mode offline aktif: menggunakan math captcha.' }}
    </p>

    @if(($captcha['provider'] ?? 'math') === 'google' && ! empty($captcha['google_site_key']))
        <div class="mt-4">
            <div class="g-recaptcha" data-sitekey="{{ $captcha['google_site_key'] }}"></div>
        </div>
    @else
        <div class="mt-4">
            <label class="mb-2 block text-sm font-semibold">Math captcha: {{ $captcha['math_question'] ?? '1 + 1' }}</label>
            <input name="math_answer" type="text" value="{{ old('math_answer') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
        </div>
    @endif

    @error('captcha')
        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>

@once
    @if(($captcha['provider'] ?? 'math') === 'google' && ! empty($captcha['google_site_key']))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endonce
