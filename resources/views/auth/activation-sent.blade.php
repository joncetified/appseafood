<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Akun</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 text-slate-900">
    <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black">Aktivasi Akun</h1>
        <p class="mt-3 text-sm text-slate-600">
            Link aktivasi sudah dikirim ke <span class="font-semibold">{{ $email ?: 'email Anda' }}</span>. Akun belum bisa dipakai sebelum link itu diklik.
        </p>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('activation.resend') }}" class="mt-8 space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Kirim Ulang ke Email</label>
                <input name="email" type="email" value="{{ old('email', $email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 font-semibold text-white">Kirim Ulang Link Aktivasi</button>
        </form>

        <a href="{{ route('login') }}" class="mt-4 block text-center text-sm font-semibold text-cyan-700">Kembali ke login</a>
    </div>
</body>
</html>
