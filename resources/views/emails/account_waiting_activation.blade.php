<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Aktivasi Akun Pegawai</title>
</head>
<body>
    <p>Halo Tim Superadmin,</p>

    <p>
        Seorang pegawai telah <strong>diajukan kembali untuk aktivasi</strong> oleh tim HRD.
        Berikut detail pegawainya:
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
        Silakan tinjau dan aktifkan akun pegawai tersebut jika sudah sesuai prosedur.
    </p>
    <p>
        <a href="{{ route('karyawan.show', $user->id_user) }}" 
           style="background:#4CAF50;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;">
           Lihat Detail
        </a>
    </p>

    <p>Salam,<br>Tim Administrator</p>
</body>
</html>
