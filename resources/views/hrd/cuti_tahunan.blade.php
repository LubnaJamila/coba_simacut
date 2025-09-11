@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Selamat datang, {{ $user->nama_lengkap }}!</h2>
        <p>Ini halaman Cuti Tahunan</p>

        <div class="row g-3 mb-4">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-calendar-event fs-2 text-warning me-2"></i>
                            <h6 class="card-title mb-0 fw-bold">Jatah Cuti Tahunan</h6>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-0">{{ $jatahCutiTahunan ?? 0 }}</h2>
                            <small class="text-muted">Hari ggftf</small>
                        </div>
                    </div>
                    <div class="bg-warning" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-calendar-event fs-2 text-dark me-2"></i>
                            <h6 class="card-title mb-0 fw-bold">Jumlah Cuti Bersama Tahunan</h6>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-0">{{ $totalCutiBersama ?? 0 }}</h2>
                            <small class="text-muted">Hari</small>
                        </div>
                    </div>
                    <div class="bg-dark" style="height: 8px; border-radius: 0 0 .5rem .5rem;"></div>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah Cuti -->
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPilihCuti"
                {{ !$adaPegawaiAktif ? 'disabled' : '' }}>
                <i class="bi bi-plus-circle"></i> Tambah Cuti
            </button>


            @if (!$adaPegawaiAktif)
                <small class="text-danger d-block mt-1">
                    Belum ada karyawan pegawai aktif. Silakan tambahkan karyawan dan pastikan disetujui super admin terlebih
                    dahulu.
                </small>
            @endif
        </div>



        <!-- Tabel -->
        @php
            use Carbon\Carbon;
            $today = Carbon::today();
        @endphp

        <table id="example" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Cuti</th>
                    <th>Tanggal Cuti Bersama</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cutiBersamaList as $index => $cuti)
                    @php
                        $cutiDate = Carbon::parse($cuti->tanggal_cuti_bersama);
                        $isPast = $cutiDate->lt($today); // true jika tanggal cuti < hari ini
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cuti->nama_cuti_bersama }}</td>
                        <td>{{ $cutiDate->format('d-m-Y') }}</td>
                        <td>
                            @if (!$isPast)
                                <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $cuti->id_cuti_bersama }}"
                                    data-nama="{{ $cuti->nama_cuti_bersama }}"
                                    data-tanggal="{{ $cuti->tanggal_cuti_bersama }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <form action="{{ route('cuti_bersama.destroy', $cuti->id_cuti_bersama) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Yakin ingin menghapus cuti bersama ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @else
                                <div class="text-muted text-wrap" style="max-width: 200px; word-break: break-word;">
                                    Tidak bisa diubah / dihapus karena tanggal cuti bersama sudah lewat
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Pilih Jenis Cuti -->
    <div class="modal fade" id="modalPilihCuti" tabindex="-1" aria-labelledby="modalPilihCutiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihCutiLabel">Pilih Jenis Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <button class="btn btn-success m-2" data-bs-toggle="modal" data-bs-target="#modalCutiUmum"
                        data-bs-dismiss="modal">
                        Cuti Tahunan Umum
                    </button>

                    <a href="{{ route('cuti_bersama') }}" class="btn btn-info m-2">
                        Cuti Tahunan Bersama
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Form Cuti Umum -->
    <div class="modal fade" id="modalCutiUmum" tabindex="-1" aria-labelledby="modalCutiUmumLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCutiUmumLabel">Tambah Cuti Tahunan Umum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cuti_tahunan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="namaCuti" class="form-label">Nama Cuti</label>
                            <input type="text" name="nama_cuti" class="form-control" id="namaCuti" value="Cuti Tahunan"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kuota" class="form-label">Kuota</label>
                            <input type="number" name="kuota" class="form-control" id="kuota"
                                placeholder="Masukkan kuota cuti" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Berlaku</label>
                            <input type="number" name="tahun" class="form-control" id="tahun"
                                value="{{ date('Y') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Cuti -->
    <div class="modal fade" id="modalEditCuti" tabindex="-1" aria-labelledby="modalEditCutiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formEditCuti">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCutiLabel">Edit Cuti Bersama</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Cuti Bersama</label>
                            <input type="text" name="nama_cuti_bersama" id="editNama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Cuti Bersama</label>
                            <input type="date" name="tanggal_cuti_bersama" id="editTanggal" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = new bootstrap.Modal(document.getElementById('modalEditCuti'));
            const formEdit = document.getElementById('formEditCuti');

            // template route dengan placeholder :id
            const routeEditTemplate = "{{ route('cuti_bersama.update', ':id') }}";

            // ambil tahun cuti tahunan dari backend
            const tahunList = @json($tahunCutiTahunanList);

            // tentukan minYear & maxYear dari array tahunList
            const minYear = Math.min(...tahunList);
            const maxYear = Math.max(...tahunList);

            // generate batas awal & akhir
            const minJenisDate = new Date(`${minYear}-01-01`);
            const maxJenisDate = new Date(`${maxYear}-12-31`);

            // ambil tanggal hari ini
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // minDate = maksimal antara hari ini dan tahun paling kecil
            const minDate = (today > minJenisDate ? today : minJenisDate)
                .toISOString()
                .split('T')[0];

            // maxDate = akhir tahun maxYear
            const maxDate = maxJenisDate.toISOString().split('T')[0];

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const nama = btn.dataset.nama;
                    const tanggal = btn.dataset.tanggal;

                    document.getElementById('editNama').value = nama;
                    document.getElementById('editTanggal').value = tanggal;

                    // set min dan max sesuai tahun jenis_cuti & tidak boleh backdate
                    document.getElementById('editTanggal').min = minDate;
                    document.getElementById('editTanggal').max = maxDate;

                    // set action form ke route Laravel
                    formEdit.action = routeEditTemplate.replace(':id', id);

                    editModal.show();
                });
            });
        });
    </script>
@endsection
