<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Aktivasi Akun</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h2>Aktivasi Akun</h2>
    <p>Halo {{ $user->name }},</p>
    <p>Akun Anda sudah dibuat. Klik tombol di bawah untuk mengaktifkan akun.</p>
    <p>
        <a href="{{ $activationUrl }}" style="display:inline-block;padding:12px 20px;background:#0f172a;color:#fff;text-decoration:none;border-radius:12px;">Aktivasi Akun</a>
    </p>
    <p>Link ini berlaku 24 jam.</p>
</body>
</html>
