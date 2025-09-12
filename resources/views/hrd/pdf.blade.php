<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Pengajuan Cuti</title>
    <style>
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        h3 { 
            text-align: center; 
            margin-bottom: 5px; 
        }
        .subtitle {
            text-align: center; 
            font-size: 11px; 
            color: #555;
            margin-bottom: 15px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: center; 
        }
        th { 
            background: #f2f2f2; 
            font-weight: bold; 
        }
        tr:nth-child(even) { 
            background: #fafafa; 
        }
        .badge { 
            padding: 3px 6px; 
            border-radius: 4px; 
            font-size: 11px;
            color: #fff;
        }
        .success { background: #28a745; }
        .danger  { background: #dc3545; }
        .warning { background: #ffc107; color: #000; }
    </style>
</head>
<body>
    <h3>Data Riwayat Pengajuan Cuti</h3>
    <div class="subtitle">
        Tahun: {{ $tahun == 'semua' ? 'Semua' : $tahun }} | 
        Status: {{ ucfirst($status) }} | 
        Divisi: {{ ucfirst($divisi) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>NIK</th>
                <th>Divisi</th>
                <th>Jenis Cuti</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Jumlah Hari</th>
                <th>Status Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cuti as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->user->nama_lengkap ?? '-' }}</td>
                    <td>{{ $row->user->nik ?? '-' }}</td>
                    <td>{{ $row->user->divisi ?? '-' }}</td>
                    <td>{{ $row->jenisCuti->nama_cuti ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_mulai_cuti)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_selesai_cuti)->format('d/m/Y') }}</td>
                    <td>{{ $row->lama_cuti }} Hari</td>
                    <td>
                        @if ($row->status_pengajuan == 'diterima')
                            <span class="badge success">Disetujui</span>
                        @elseif($row->status_pengajuan == 'ditolak')
                            <span class="badge danger">Ditolak</span>
                        @else
                            <span class="badge warning">Menunggu</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data cuti ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
