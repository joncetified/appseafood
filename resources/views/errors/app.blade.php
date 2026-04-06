<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error {{ $status ?? 500 }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-4 text-white">
    <div class="w-full max-w-2xl rounded-[2rem] border border-white/10 bg-white/5 p-10 shadow-2xl backdrop-blur">
        <p class="text-sm uppercase tracking-[0.35em] text-cyan-300">System Error</p>
        <h1 class="mt-4 text-6xl font-black">{{ $status ?? 500 }}</h1>
        <p class="mt-6 text-lg text-slate-200">{{ $message ?? 'Terjadi kesalahan pada aplikasi.' }}</p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('home') }}" class="rounded-2xl bg-cyan-500 px-5 py-3 font-semibold text-slate-950">Kembali ke Beranda</a>
            <a href="{{ url()->previous() }}" class="rounded-2xl border border-white/15 px-5 py-3 font-semibold text-white">Kembali</a>
        </div>
    </div>
</body>
</html>
