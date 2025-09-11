@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h3 class="mb-4">Detail Akun Karyawan</h3>

        <div class="row g-3">

            <!-- Data Pribadi -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-bold">Data Pribadi Karyawan</div>
                    <div class="card-body">
                        <p><strong>Nama Lengkap Karyawan:</strong> {{ $karyawan->nama_lengkap }}</p>
                        <p><strong>NIK Karyawan:</strong> {{ $karyawan->nik }}</p>
                        <p><strong>No Telp:</strong> {{ $karyawan->no_telp }}</p>
                        <p><strong>Alamat:</strong> {{ $karyawan->alamat }}</p>
                        <p><strong>Jenis Kelamin:</strong> {{ $karyawan->jenis_kelamin }}</p>
                        <p><strong>No NPWP:</strong> {{ $karyawan->no_npwp }}</p>
                        <p><strong>Tanggal Kontrak Kerja:</strong>
                            {{ $karyawan->tanggal_mulai_kerja?->format('Y-m-d') }}
                            s/d
                            {{ $karyawan->tanggal_selesai_kerja?->format('Y-m-d') }}
                        </p>
                        <p><strong>Divisi:</strong> {{ $karyawan->divisi }}</p>
                        <p><strong>Jabatan:</strong> {{ $karyawan->jabatan }}</p>
                        <p><strong>Status Karyawan:</strong> {{ $karyawan->status_karyawan }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Akun -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-bold">Data Akun</div>
                    <div class="card-body">
                        <p><strong>Role User:</strong> {{ $karyawan->role_user }}</p>
                        <p><strong>Status User:</strong> {{ $karyawan->status_akun }}</p>
                        <p><strong>Email:</strong> {{ $karyawan->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Lampiran Pendukung -->
            <div class="col-md-12">
                <div class="card shadow-sm mt-3">
                    <div class="card-header fw-bold">Lampiran Pendukung</div>
                    <div class="card-body d-flex flex-wrap gap-4">

                        <div>
                            <p class="fw-bold">KTP</p>
                            @if ($karyawan->file_ktp)
                                <img src="{{ asset($karyawan->file_ktp) }}" alt="KTP"
                                    class="img-fluid rounded shadow-sm" style="max-width: 250px;">
                            @else
                                <p>-</p>
                            @endif
                        </div>

                        <div>
                            <p class="fw-bold">NPWP</p>
                            @if ($karyawan->file_npwp)
                                <img src="{{ asset($karyawan->file_npwp) }}" alt="NPWP"
                                    class="img-fluid rounded shadow-sm" style="max-width: 250px;">
                            @else
                                <p>-</p>
                            @endif
                        </div>

                        <div>
                            <p class="fw-bold">SK Pengantar Kontrak</p>
                            @if ($karyawan->file_sk_kontrak)
                                <!-- Gunakan iframe agar toolbar PDF viewer muncul -->
                                <iframe src="{{ asset($karyawan->file_sk_kontrak) }}" width="450px" height="500px"
                                    style="border: none;">
                                </iframe>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2" style="justify-content: end">
                <!-- Form Terima -->
                <form action="{{ route('karyawan.setuju', $karyawan->id_user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Terima
                    </button>
                </form>

                <!-- Form Tolak -->
                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
