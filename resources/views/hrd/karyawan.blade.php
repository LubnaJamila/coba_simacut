@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Selamat datang, {{ $user->nama_lengkap }}!</h2>
        <p>Ini halaman Karyawan</p>
        <!-- Tombol Tambah Karyawan -->
        <div class="mb-3">
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Karyawan
            </a>
        </div>

        <!-- Tabel Karyawan -->
        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role User</th>
                    <th>Status Akun</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->divisi ?? '-' }}</td>
                        <td>{{ $item->jabatan ?? '-' }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->no_telp ?? '-' }}</td>
                        <td>{{ $item->role_user }}</td>
                        <td>
                            @php
                                $status = $item->status_akun; // misal status disimpan string: 'pengaktifan', 'aktif', 'penonaktifan', 'nonaktif'
                                $badgeClass = match ($status) {
                                    'Waiting-Activation' => 'bg-warning text-dark',
                                    'Active' => 'bg-success',
                                    'Non-Active' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>
                            <!-- Tombol Detail -->
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                            <!-- Tombol Edit -->
                            <a href="{{ route('karyawan.edit', $item->id_user) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
