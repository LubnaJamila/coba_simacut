@extends('layouts.app')

@section('content')
    <h2>Selamat datang, {{ $user->nama_lengkap }}!</h2>
    <p>Ini halaman dashboard, menu di sidebar menyesuaikan role user.</p>

    <div class="row g-3 mb-4">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event fs-2 text-success me-2"></i>
                        <h6 class="card-title mb-0 fw-bold">Jatah Cuti Tahunan</h6>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $totalActive ?? 0 }}</h2>
                        <small class="text-muted">Hari ggftf</small>
                    </div>
                </div>
                <div class="bg-success" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event fs-2 text-warning me-2"></i>
                        <h6 class="card-title mb-0 fw-bold">Jumlah Cuti Bersama Tahunan</h6>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $totalWaiting ?? 0 }}</h2>
                        <small class="text-muted">Hari</small>
                    </div>
                </div>
                <div class="bg-warning" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event fs-2 text-danger me-2"></i>
                        <h6 class="card-title mb-0 fw-bold">Jumlah Cuti Bersama Tahunan</h6>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $totalNonActive ?? 0 }}</h2>
                        <small class="text-muted">Hari</small>
                    </div>
                </div>
                <div class="bg-danger" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
            </div>
        </div>
    </div>

    <!-- Tabel Karyawan -->
    <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Status Karyawan</th>
                <th>Tanggal Mulai Kerja</th>
                <th>Tanggal Selesai Kerja</th>
                <th>Divisi</th>
                <th>Jabatan</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->status_karyawan }}</td>
                    <td>{{ $item->tanggal_mulai_kerja?->format('Y-m-d') }}</td>
                    <td>
                        @if ($item->tanggal_selesai_kerja)
                            {{ $item->tanggal_selesai_kerja->format('Y-m-d') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->divisi ?? '-' }}</td>
                    <td>{{ $item->jabatan ?? '-' }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->no_telp ?? '-' }}</td>
                    <td>
                        <!-- Tombol Detail -->
                        <a href="{{ route('karyawan.show', $item->id_user) }}" class="btn btn-sm btn-info">
                            Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
