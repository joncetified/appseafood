<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h2>Reset Password</h2>
    <p>Halo {{ $user->name }},</p>
    <p>Klik tombol di bawah untuk mengganti password akun Anda.</p>
    <p>
        <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;background:#0f172a;color:#fff;text-decoration:none;border-radius:12px;">Reset Password</a>
    </p>
    <p>Link ini berlaku 1 jam.</p>
</body>
</html>
