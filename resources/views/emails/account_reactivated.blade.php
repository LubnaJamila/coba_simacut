<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akun Diaktifkan Kembali</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333; line-height:1.6;">
    <h2>Halo, {{ $user->nama_lengkap }}</h2>

    <p>Akun Anda telah <b>diaktifkan kembali</b> oleh admin.<br>
    Sekarang Anda sudah bisa login menggunakan <b>email dan password lama</b>.</p>

    @if($user->email)
    <p><b>Email Login:</b> {{ $user->email }}</p>
    @endif

    <p style="background:#fff3cd; padding:10px; border:1px solid #ffeeba; border-radius:5px; color:#856404;">
        ⚠️ Jika Anda lupa password, silakan gunakan menu <b>Lupa Password</b> pada halaman login untuk reset password dengan OTP.
    </p>

    <p>
        <a href="{{ url('/login') }}" 
           style="background:#4CAF50;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;">
           Login Sekarang
        </a>
    </p>

    <p>Terima kasih,<br>
    {{ config('app.name') }}</p>
</body>
</html>
