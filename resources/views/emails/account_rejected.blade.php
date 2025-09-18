<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Akun Ditolak</title>
</head>
<body>
    <p>Halo Tim HRD,</p>

    <p>
        Pengajuan akun untuk pegawai berikut <strong>tidak dapat kami setujui saat ini</strong>:
    </p>

    <table style="border-collapse: collapse; margin: 10px 0;">
        <tr>
            <td style="padding: 4px 8px;"><strong>Nama Lengkap</strong></td>
            <td style="padding: 4px 8px;">: {{ $user->nama_lengkap }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>NIK</strong></td>
            <td style="padding: 4px 8px;">: {{ $user->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Jabatan</strong></td>
            <td style="padding: 4px 8px;">: {{ $user->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Divisi</strong></td>
            <td style="padding: 4px 8px;">: {{ $user->divisi ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 8px;"><strong>Email</strong></td>
            <td style="padding: 4px 8px;">: {{ $user->email }}</td>
        </tr>
    </table>

    <p>
        Jika ingin mengajukan kembali, silakan lakukan pengajuan ulang 
        sesuai prosedur yang berlaku.
    </p>

    <p>Salam,<br>
    Tim Administrator</p>
</body>
</html>
