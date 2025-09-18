<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Akun Baru</title>
</head>
<body>
    <p>Halo Tim Superadmin,</p>

    <p>
        HRD telah mengajukan akun baru untuk pegawai berikut:
    </p>

    <table style="border-collapse: collapse; margin: 10px 0;">
        <tr>
            <td style="padding: 4px 8px;"><strong>Nama Lengkap</strong></td>
            <td style="padding: 4px 8px;">: {{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>NIK</strong></td>
            <td style="padding: 4px 8px;">: {{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Jabatan</strong></td>
            <td style="padding: 4px 8px;">: {{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Divisi</strong></td>
            <td style="padding: 4px 8px;">: {{ $karyawan->divisi }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Email</strong></td>
            <td style="padding: 4px 8px;">: {{ $karyawan->email }}</td>
        </tr>
    </table>

    <p>
        Silakan masuk ke sistem untuk melakukan aktivasi akun tersebut.
    </p>
    <p>
        <a href="{{ route('karyawan.show', $karyawan->id_user) }}" 
           style="background:#4CAF50;color:white;padding:10px 15px;text-decoration:none;border-radius:5px;">
           Lihat Detail
        </a>
    </p>

    <p>Salam,<br>Tim Sistem</p>
</body>
</html>
