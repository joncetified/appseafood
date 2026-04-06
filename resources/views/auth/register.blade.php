<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Seafood</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 text-slate-900">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black">Daftar Pelanggan</h1>
        <p class="mt-2 text-sm text-slate-500">Akun pelanggan harus diaktivasi lewat link email sebelum bisa dipakai.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Nama</label>
                <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">WhatsApp</label>
                <input name="whatsapp_number" type="text" value="{{ old('whatsapp_number') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Password</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Konfirmasi Password</label>
                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>

            @include('auth.partials.captcha')

            <button type="submit" class="w-full rounded-2xl bg-cyan-700 px-4 py-3 font-semibold text-white">Daftar</button>
        </form>
    </div>
</body>
</html>
