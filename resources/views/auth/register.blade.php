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
        <p class="mt-2 text-sm text-slate-500">Akun baru akan otomatis masuk role pelanggan.</p>

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
                <label class="mb-2 block text-sm font-semibold">Password</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Konfirmasi Password</label>
                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-cyan-700 px-4 py-3 font-semibold text-white">Daftar</button>
        </form>
    </div>
</body>
</html>
