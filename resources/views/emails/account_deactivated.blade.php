<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akun Pegawai Dinonaktifkan</title>
</head>
<body>
    <p>Halo,</p>

    <p>
        Akun pegawai berikut telah <strong>dinonaktifkan</strong> oleh tim HRD:
    </p>

    <ul>
        <li><strong>Nama:</strong> {{ $user->nama_lengkap }}</li>
        <li><strong>NIK:</strong> {{ $user->nik }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Divisi:</strong> {{ $user->divisi }}</li>
        <li><strong>Jabatan:</strong> {{ $user->jabatan }}</li>
        <li><strong>Status Akun Saat Ini:</strong> {{ $user->status_akun }}</li>
    </ul>

    <p>
        Pegawai ini sudah tidak dapat mengakses sistem hingga status akunnya diaktifkan kembali oleh tim Superadmin.
    </p>

    <p>Salam,<br>Tim Administrator</p>
</body>
</html>
