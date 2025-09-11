@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">Data Karyawan & Status Block Leave (Tahun {{ $tahun }})</h3>

        <div class="row">
            {{-- Total Karyawan --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Total Karyawan
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="text-center flex-fill">
                            <h3 class="text-primary fw-bold">{{ $summary['karyawan']['aktif'] }}</h3>
                            <p class="mb-0">Aktif</p>
                        </div>
                        <div class="text-center flex-fill">
                            <h3 class="text-secondary fw-bold">{{ $summary['karyawan']['nonaktif'] }}</h3>
                            <p class="mb-0">Non-Aktif</p>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-primary text-white fw-semibold">
                        Tahun Ini
                    </div>
                </div>
            </div>

            {{-- Pengajuan Cuti --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white fw-bold">
                        Pengajuan Cuti
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="text-center flex-fill">
                            <h3 class="text-success fw-bold">{{ $summary['pengajuan']['diterima'] }}</h3>
                            <p class="mb-0">Disetujui</p>
                        </div>
                        <div class="text-center flex-fill">
                            <h3 class="text-danger fw-bold">{{ $summary['pengajuan']['ditolak'] }}</h3>
                            <p class="mb-0">Ditolak</p>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-success text-white fw-semibold">
                        Tahun Ini
                    </div>
                </div>
            </div>

            {{-- Block Leave --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark fw-bold">
                        Block Leave
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="text-center flex-fill">
                            <h3 class="text-warning fw-bold">{{ $summary['block_leave']['sudah'] }}</h3>
                            <p class="mb-0">Sudah Ambil</p>
                        </div>
                        <div class="text-center flex-fill">
                            <h3 class="text-info fw-bold">{{ $summary['block_leave']['belum'] }}</h3>
                            <p class="mb-0">Belum Ambil</p>
                        </div>
                    </div>
                    <div class="card-footer text-center bg-warning fw-semibold">
                        Tahun Ini
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="tahun" class="form-select" onchange="this.form.submit()">
                        @foreach ($tahunCutiTahunanList as $t)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="divisi" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Divisi --</option>
                        @foreach ($divisiList as $d)
                            <option value="{{ $d }}" {{ $d == $divisi ? 'selected' : '' }}>
                                {{ $d }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <span class="fw-bold">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </form>

        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th>Cuti Tahunan</th>
                    <th>Cuti Non-Tahunan</th>
                    <th>Periode Block Leave</th>
                    <th>Status Block Leave</th>
                </tr>

            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['divisi'] ?? '-' }}</td>
                        <td>{{ $row['cuti_tahunan'] }} Hari</td>
                        <td>{{ $row['cuti_non_tahunan'] }} Hari</td>
                        <td>
                            @if ($row['periode_block_leave'])
                                {{ $row['periode_block_leave'] }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($row['block_leave'])
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger"></i>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data karyawan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
