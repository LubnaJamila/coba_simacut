@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Form Tambah Karyawan</h2>

        {{-- Pesan error dari try-catch --}}
        @if ($errors->has('custom_error'))
            <div class="alert alert-danger">
                {{ $errors->first('custom_error') }}
            </div>
        @endif

        {{-- Pesan error validasi bawaan --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        @if ($error !== $errors->first('custom_error'))
                            {{-- jangan dobel --}}
                            <li>{{ $error }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formKaryawan" action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama, NIK, Telp --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                    <small class="text-danger d-none" id="namaError">Nama wajib diisi</small>
                </div>
                <div class="col-md-4">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" required minlength="16"
                        maxlength="16" pattern="[0-9]{16}">
                    <small class="text-danger d-none" id="nikError">NIK harus 16 digit angka</small>
                </div>
                <div class="col-md-4">
                    <label for="no_telp" class="form-label">No. Telp</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp" pattern="[0-9]{10,15}">
                    <small class="text-danger d-none" id="telpError">No. Telp harus angka (10–15 digit)</small>
                </div>
            </div>

            {{-- Alamat, Jenis Kelamin, NPWP --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat">
                </div>
                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih</option>
                        <option value="Laki-Laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <small class="text-danger d-none" id="jkError">Jenis kelamin wajib dipilih</small>
                </div>
                <div class="col-md-4">
                    <label for="no_npwp" class="form-label">No. NPWP</label>
                    <input type="text" class="form-control" id="no_npwp" name="no_npwp" pattern="[0-9]{15}">
                    <small class="text-danger d-none" id="npwpError">NPWP harus 15 digit angka (opsional)</small>
                </div>
            </div>

            {{-- Tanggal Mulai & Selesai Kontrak --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="status_karyawan" class="form-label">Status Karyawan</label>
                    <select class="form-select" id="status_karyawan" name="status_karyawan" required>
                        <option value="">Pilih</option>
                        <option value="probation">Probation</option>
                        <option value="PKWT">PKWT</option>
                        <option value="PKWTT">PKWTT</option>
                    </select>
                    <small class="text-danger d-none" id="statusKaryawanError">Jenis kelamin wajib dipilih</small>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_mulai_kerja" class="form-label">Tanggal Mulai Kontrak</label>
                    <input type="date" class="form-control" id="tanggal_mulai_kerja" name="tanggal_mulai_kerja" required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai_kerja" class="form-label">Tanggal Selesai Kontrak</label>
                    <input type="date" class="form-control" id="tanggal_selesai_kerja" name="tanggal_selesai_kerja"
                        required>
                    <small class="text-danger d-none" id="tglError">Tanggal selesai harus >= tanggal mulai</small>
                </div>
            </div>

            {{-- Divisi & Jabatan --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="divisi" class="form-label">Divisi</label>
                    <input type="text" class="form-control" id="divisi" name="divisi">
                </div>
                <div class="col-md-6">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan">
                </div>
            </div>

            {{-- File Upload --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="file_ktp" class="form-label">KTP</label>
                    <input type="file" class="form-control" id="file_ktp" name="file_ktp"
                        accept=".jpg,.jpeg,.png,.pdf" required>
                    <small class="text-danger d-none" id="ktpError">File harus JPG, JPEG, PNG, PDF & maksimal 2MB</small>
                </div>
                <div class="col-md-4">
                    <label for="file_npwp" class="form-label">NPWP</label>
                    <input type="file" class="form-control" id="file_npwp" name="file_npwp"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-danger d-none" id="npwpFileError">File harus JPG, JPEG, PNG, PDF & maksimal
                        2MB</small>
                </div>
                <div class="col-md-4">
                    <label for="file_sk_kontrak" class="form-label">SK Kontrak</label>
                    <input type="file" class="form-control" id="file_sk_kontrak" name="file_sk_kontrak"
                        accept=".pdf" required>
                    <small class="text-danger d-none" id="skError">File harus PDF & maksimal 2MB</small>
                </div>
            </div>

            {{-- Role, Status, Email, Password --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="role_user" class="form-label">Role User</label>
                    <select class="form-select" id="role_user" name="role_user" required>
                        <option value="">Pilih</option>
                        <option value="atasan">Atasan</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
                    <small class="text-danger d-none" id="roleError">Role wajib dipilih</small>
                </div>
                <div class="col-md-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <small class="text-danger d-none" id="emailError">Format email tidak valid</small>
                </div>
                <div class="col-md-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <small class="text-danger d-none" id="passwordError">Password minimal 6 karakter</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function toggleError(el, condition) {
                if (condition) el.classList.remove("d-none");
                else el.classList.add("d-none");
            }

            const nik = document.getElementById("nik");
            const nikError = document.getElementById("nikError");

            const telp = document.getElementById("no_telp");
            const telpError = document.getElementById("telpError");

            const npwp = document.getElementById("no_npwp");
            const npwpError = document.getElementById("npwpError");

            const email = document.getElementById("email");
            const emailError = document.getElementById("emailError");

            const password = document.getElementById("password");
            const passwordError = document.getElementById("passwordError");

            const tglMulai = document.getElementById("tanggal_mulai_kerja");
            const tglSelesai = document.getElementById("tanggal_selesai_kerja");
            const tglError = document.getElementById("tglError");

            const statusKaryawan = document.getElementById("status_karyawan");
            const statusKaryawanError = document.getElementById("statusKaryawanError");

            // --- Validasi input dasar ---
            nik.addEventListener("input", function() {
                toggleError(nikError, !/^[0-9]{16}$/.test(nik.value));
            });

            telp.addEventListener("input", function() {
                toggleError(telpError, telp.value !== "" && !/^[0-9]{10,15}$/.test(telp.value));
            });

            npwp.addEventListener("input", function() {
                toggleError(npwpError, npwp.value !== "" && !/^[0-9]{15}$/.test(npwp.value));
            });

            email.addEventListener("input", function() {
                toggleError(emailError, !email.validity.valid);
            });

            password.addEventListener("input", function() {
                toggleError(passwordError, password.value.length < 6);
            });

            // --- Validasi tanggal ---
            tglMulai.addEventListener('change', () => {
                tglSelesai.min = tglMulai.value;
                if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                    tglSelesai.value = tglMulai.value;
                }
            });

            tglSelesai.addEventListener('change', () => {
                toggleError(tglError, tglSelesai.value && tglSelesai.value < tglMulai.value);
            });

            // --- Atur tanggal selesai kerja berdasarkan status ---
            statusKaryawan.addEventListener("change", function() {
                if (this.value === "PKWTT") {
                    tglSelesai.disabled = true;
                    tglSelesai.required = false;
                    tglSelesai.value = "";
                    toggleError(tglError, false);
                } else {
                    tglSelesai.disabled = false;
                    tglSelesai.required = true;
                    tglSelesai.min = tglMulai.value;
                }
                toggleError(statusKaryawanError, this.value === "");
            });

            // --- Validasi file ---
            function validateFile(input, allowedTypes, maxMB, errorEl) {
                input.addEventListener("change", function() {
                    const file = input.files[0];
                    if (!file) {
                        errorEl.classList.add("d-none");
                        return;
                    }
                    const typeValid = allowedTypes.includes(file.type);
                    const sizeValid = file.size <= maxMB * 1024 * 1024;
                    toggleError(errorEl, !(typeValid && sizeValid));
                });
            }

            validateFile(document.getElementById("file_ktp"),
                ["image/jpeg", "image/png", "application/pdf"], 2, document.getElementById("ktpError"));

            validateFile(document.getElementById("file_npwp"),
                ["image/jpeg", "image/png", "application/pdf"], 2, document.getElementById("npwpFileError"));

            validateFile(document.getElementById("file_sk_kontrak"),
                ["application/pdf"], 2, document.getElementById("skError"));

            // --- Auto uppercase untuk text ---
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });
        });
    </script>
@endsection
