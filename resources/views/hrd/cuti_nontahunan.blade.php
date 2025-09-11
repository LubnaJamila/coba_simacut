@extends('layouts.app')

@section('content')
    <h2>Selamat datang, {{ $user->nama_lengkap }}!</h2>
    <p>Ini halaman Cuti Non Tahunan, menu di sidebar menyesuaikan role user.</p>

    <div class="row g-3 mb-4">
        <!-- Card 1 -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event fs-2 text-warning me-2"></i>
                        <h6 class="card-title mb-0 fw-bold">Jumlah Jenis Cuti Non-Tahunan</h6>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $totalCutiNontahunan ?? 0 }}</h2>
                        <small class="text-muted">Hari</small>
                    </div>
                </div>
                <div class="bg-warning" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
            </div>
        </div>
    </div>

    <!-- Tombol Tambah Cuti -->
    <div class="mb-3">
        <a href="{{ route('cuti_nontahunan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Cuti
        </a>
    </div>

    <!-- Tabel -->
    <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Cuti</th>
                <th>Kuota Cuti</th>
                <th>Tipe Penggunaan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cutiNonTahunan as $index => $cuti)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cuti->nama_cuti }}</td>
                    <td>{{ $cuti->kuota ?? '-' }}</td>
                    <td>{{ $cuti->tipe_penggunaan ?? '-' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                        <form action="#" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
