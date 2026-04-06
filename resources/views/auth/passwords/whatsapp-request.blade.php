<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset via WhatsApp</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-100 px-4 text-slate-900">
    <div class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-3xl font-black">Reset via WhatsApp</h1>
        <p class="mt-3 text-sm text-slate-600">
            Permintaan reset untuk <span class="font-semibold">{{ $email }}</span> sudah disiapkan. Tekan tombol di bawah untuk meneruskan link reset ke WhatsApp support.
        </p>

        <a href="{{ $whatsappUrl }}" target="_blank" rel="noreferrer" class="mt-8 block rounded-2xl bg-emerald-600 px-4 py-3 text-center font-semibold text-white">
            Kirim ke WhatsApp Support
        </a>

        <p class="mt-4 text-xs text-slate-500">
            Nomor tujuan: {{ $supportWhatsapp }}
        </p>

        <a href="{{ route('login') }}" class="mt-6 block text-center text-sm font-semibold text-cyan-700">Kembali ke login</a>
    </div>
</body>
</html>
