<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akun Aktif</title>
</head>
<body>
    <h2>Halo, {{ $user->name }}</h2>
    <p>Akun Anda dengan email <b>{{ $user->email }}</b> telah berhasil diaktifkan.</p>

    <p>Password sementara Anda adalah:</p>
    <pre style="font-size:16px; font-weight:bold; background:#f4f4f4; padding:10px; display:inline-block;">
        {{ $passwordTemporary }}
    </pre>

    <p>Silakan klik tombol di bawah ini untuk mengganti password Anda:</p>
    <p>
        <a href="{{ $changePasswordUrl }}" 
           style="background:#4CAF50;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;">
           Ganti Password
        </a>
    </p>

    <p>Link ini berlaku selama 24 jam.</p>
</body>
</html>
