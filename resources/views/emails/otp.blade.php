<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Reset Password</title>
</head>
<body>
    <h2>Halo, {{ $user->name }}</h2>

    <p>Kode OTP Anda adalah:</p>

    <h1 style="background: #f3f3f3; padding: 10px; text-align: center; border-radius: 8px; display: inline-block;">
        {{ $otp }}
    </h1>

    <p>Kode ini berlaku selama <strong>5 menit</strong>.  
    Jangan bagikan kode ini kepada siapapun.</p>

    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

    <br>
    <p>Terima kasih,<br>
    {{ config('app.name') }}</p>
</body>
</html>
