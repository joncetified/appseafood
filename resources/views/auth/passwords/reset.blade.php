<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Reset Password</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 text-slate-900">
    <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black">Form Reset Password</h1>
        <p class="mt-2 text-sm text-slate-500">Masukkan password baru untuk akun Anda.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset.update') }}" class="mt-8 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input name="email" type="email" value="{{ old('email', $email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Password Baru</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Konfirmasi Password Baru</label>
                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 font-semibold text-white">Simpan Password Baru</button>
        </form>
    </div>
</body>
</html>
