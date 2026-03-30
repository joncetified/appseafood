<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner Login</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4 text-slate-900">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
        <h1 class="text-3xl font-black">Owner Login</h1>
        <p class="mt-2 text-sm text-slate-500">Akses ini dipakai untuk masuk ke panel pengelolaan sistem.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold">Password</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" required>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 font-semibold text-white">Login</button>
        </form>

        <p class="mt-6 text-sm text-slate-500">
            Belum punya akun pelanggan?
            <a href="{{ route('register') }}" class="font-semibold text-cyan-700">Daftar</a>
        </p>

    </div>
</body>
</html>
