@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Data Riwayat Pengajuan Cuti</h5>
            <div>
                <span>{{ now()->format('d F Y') }}</span>
                <a href="{{ route('cuti.exportPdf', ['tahun' => $tahun, 'status' => $status, 'divisi' => $divisi]) }}"
                    class="btn btn-danger {{ $cuti->isEmpty() ? 'disabled' : '' }}"
                    @if ($cuti->isEmpty()) aria-disabled="true" onclick="return false;" @endif>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('riwayat_pengajuan.index') }}" class="d-flex mb-3">
            {{-- Tahun --}}
            <select name="tahun" class="form-select me-2" onchange="this.form.submit()">
                @foreach ($listTahun as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t == 'semua' ? 'Semua' : $t }}
                    </option>
                @endforeach
            </select>

            {{-- Status --}}
            <select name="status" class="form-select me-2" onchange="this.form.submit()">
                <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua</option>
                <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            </select>

            {{-- Divisi --}}
            <select name="divisi" class="form-select me-2" onchange="this.form.submit()">
                @foreach ($listDivisi as $d)
                    <option value="{{ $d }}" {{ $divisi == $d ? 'selected' : '' }}>
                        {{ $d == 'semua' ? 'Semua' : $d }}
                    </option>
                @endforeach
            </select>
        </form>

        <!-- Table -->
        <table id="example" class="table table-striped">
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
                @foreach ($cuti as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->user->nama_lengkap }}</td>
                        <td>{{ $row->user->nik }}</td>
                        <td>{{ $row->user->divisi }}</td>
                        <td>{{ $row->jenisCuti->nama_cuti }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_mulai_cuti)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_selesai_cuti)->format('d/m/Y') }}</td>
                        <td>{{ $row->lama_cuti }} Hari</td>
                        <td>
                            @if ($row->status_pengajuan == 'diterima')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($row->status_pengajuan == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const form = document.getElementById('filterForm');
        const selects = form.querySelectorAll('select');

        selects.forEach(select => {
            select.addEventListener('change', () => {
                // submit tanpa query string lama
                const params = new URLSearchParams();
                selects.forEach(s => params.append(s.name, s.value));
                window.location.href = form.action + '?' + params.toString();
            });
        });
    </script>
@endsection
