<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 text-slate-900">
    <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black">Reset Password</h1>
        <p class="mt-2 text-sm text-slate-500">Pilih reset via email atau permintaan WhatsApp.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Metode Reset</label>
                <select name="delivery_method" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="email" @selected(old('delivery_method') === 'email')>Email</option>
                    <option value="whatsapp" @selected(old('delivery_method') === 'whatsapp')>WhatsApp</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Nomor WhatsApp</label>
                <input name="whatsapp_number" type="text" value="{{ old('whatsapp_number') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                <p class="mt-2 text-xs text-slate-500">Untuk metode WhatsApp, email dan nomor harus cocok dengan data akun.</p>
            </div>

            @include('auth.partials.captcha')

            @if($supportWhatsapp)
                <div class="rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-xs text-cyan-700">
                    WhatsApp support terdaftar: {{ $supportWhatsapp }}
                </div>
            @endif

            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 font-semibold text-white">Proses Reset Password</button>
        </form>

        <a href="{{ route('login') }}" class="mt-6 block text-center text-sm font-semibold text-cyan-700">Kembali ke login</a>
    </div>
</body>
</html>
