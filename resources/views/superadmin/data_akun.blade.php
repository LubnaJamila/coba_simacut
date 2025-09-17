@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Data Akun Karyawan</h4>

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status_akun') == 'Active' || request()->get('status_akun') == null ? 'Active' : '' }}" 
               href="{{ route('data_akun_active', ['status_akun' => 'Active']) }}">
                Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status_akun') == 'Non-Active' ? 'active' : '' }}" 
               href="{{ route('data_akun_active', ['status_akun' => 'Non-Active']) }}">
                Tidak Aktif
            </a>
        </li>
    </ul>

    <div class="table-responsive mt-3">
        <table id="example" class="table table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Role User</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawans as $index => $karyawan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $karyawan->nama_lengkap }}</td>
                        <td>{{ $karyawan->role_user }}</td>
                        <td>{{ $karyawan->divisi ?? '-' }}</td>
                        <td>{{ $karyawan->jabatan ?? '-' }}</td>
                        <td>{{ $karyawan->email }}</td>
                        <td>{{ $karyawan->no_telp }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
