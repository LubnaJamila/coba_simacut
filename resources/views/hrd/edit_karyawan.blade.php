@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Form Edit Karyawan</h2>

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

        <form id="formKaryawan" action="{{ route('karyawan.update', $karyawan->id_user) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama, NIK, Telp --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                        value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                    <small class="text-danger d-none" id="namaError">Nama wajib diisi</small>
                </div>
                <div class="col-md-4">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik"
                        value="{{ old('nik', $karyawan->nik) }}" required minlength="16" maxlength="16" pattern="[0-9]{16}">
                    <small class="text-danger d-none" id="nikError">NIK harus 16 digit angka</small>
                </div>
                <div class="col-md-4">
                    <label for="no_telp" class="form-label">No. Telpon</label>
                    <input type="text" class="form-control" id="no_telp" name="no_telp"
                        value="{{ old('no_telp', $karyawan->no_telp) }}" pattern="[0-9]{10,15}">
                    <small class="text-danger d-none" id="telpError">No. Telp harus angka (10–15 digit)</small>
                </div>
            </div>

            {{-- Alamat, Jenis Kelamin, NPWP --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                        value="{{ old('alamat', $karyawan->alamat) }}">
                    <small class="text-danger d-none" id="alamatError">Alamat minimal 5 karakter</small>
                </div>
                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih</option>
                        <option value="Laki-Laki"
                            {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    <small class="text-danger d-none" id="jkError">Jenis kelamin wajib dipilih</small>
                </div>
                <div class="col-md-4">
                    <label for="no_npwp" class="form-label">No. NPWP</label>
                    <input type="text" class="form-control" id="no_npwp" name="no_npwp"
                        value="{{ old('no_npwp', $karyawan->no_npwp) }}" pattern="[0-9]{15}">
                    <small class="text-danger d-none" id="npwpError">NPWP harus 15 digit angka (opsional)</small>
                </div>
            </div>

            {{-- Tanggal Mulai & Selesai Kontrak --}}
            {{-- Status Karyawan + Tanggal Kontrak --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="status_karyawan" class="form-label">Status Karyawan</label>
                    <select class="form-select" id="status_karyawan" name="status_karyawan" required>
                        <option value="">Pilih</option>
                        <option value="Probation"
                            {{ old('status_karyawan', $karyawan->status_karyawan) == 'Probation' ? 'selected' : '' }}>
                            Probation
                        </option>
                        <option value="PKWT"
                            {{ old('status_karyawan', $karyawan->status_karyawan) == 'PKWT' ? 'selected' : '' }}>PKWT
                        </option>
                        <option value="PKWTT"
                            {{ old('status_karyawan', $karyawan->status_karyawan) == 'PKWTT' ? 'selected' : '' }}>PKWTT
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_mulai_kerja" class="form-label">Tanggal Mulai Kontrak</label>
                    <input type="date" class="form-control" id="tanggal_mulai_kerja" name="tanggal_mulai_kerja"
                        value="{{ old('tanggal_mulai_kerja', $karyawan->tanggal_mulai_kerja ? \Carbon\Carbon::parse($karyawan->tanggal_mulai_kerja)->format('Y-m-d') : '') }}"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai_kerja" class="form-label">Tanggal Selesai Kontrak</label>
                    <input type="date" class="form-control" id="tanggal_selesai_kerja" name="tanggal_selesai_kerja"
                        value="{{ old('tanggal_selesai_kerja', $karyawan->tanggal_selesai_kerja ? \Carbon\Carbon::parse($karyawan->tanggal_selesai_kerja)->format('Y-m-d') : '') }}">
                    <small class="text-danger d-none" id="tglError">Tanggal selesai harus >= tanggal mulai</small>
                </div>
            </div>

            {{-- Divisi & Jabatan --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="divisi" class="form-label">Divisi</label>
                    <input type="text" class="form-control" id="divisi" name="divisi"
                        value="{{ old('divisi', $karyawan->divisi) }}">
                    <small class="text-danger d-none" id="divisiError">Divisi wajib diisi</small>
                </div>
                <div class="col-md-6">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan"
                        value="{{ old('jabatan', $karyawan->jabatan) }}">
                    <small class="text-danger d-none" id="jabatanError">Jabatan wajib diisi</small>
                </div>
            </div>


            {{-- File Upload dengan Preview --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="file_ktp" class="form-label">KTP</label>
                    <input type="file" class="form-control" id="file_ktp" name="file_ktp"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-danger d-none" id="ktpError">File harus JPG, JPEG, PNG, PDF & maksimal 2MB</small>
                    <div id="previewKtpContainer" class="mt-2">
                        <img src="{{ asset($karyawan->file_ktp) }}" alt="Preview KTP" class="img-thumbnail mt-2"
                            style="max-width: 200px;">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="file_npwp" class="form-label">NPWP</label>
                    <input type="file" class="form-control" id="file_npwp" name="file_npwp"
                        accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-danger d-none" id="npwpFileError">File harus JPG, JPEG, PNG, PDF & maksimal
                        2MB</small>
                    <div id="previewNpwpContainer" class="mt-2">
                        <img src="{{ asset($karyawan->file_npwp) }}" alt="Preview KTP" class="img-thumbnail mt-2"
                            style="max-width: 200px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="file_sk_kontrak" class="form-label">SK Kontrak</label>
                    <input type="file" class="form-control" id="file_sk_kontrak" name="file_sk_kontrak"
                        accept=".pdf">
                    <small class="text-danger d-none" id="skError">File harus PDF & maksimal 2MB</small>
                    <div id="previewContainer" class="border p-2 rounded"
                        style="height:300px; {{ $karyawan->file_sk_kontrak ? '' : 'display:none;' }}">
                        <embed id="pdfPreview"
                            src="{{ $karyawan->file_sk_kontrak ? asset($karyawan->file_sk_kontrak) : '' }}"
                            type="application/pdf" width="100%" height="100%">
                    </div>
                </div>
            </div>
            {{-- Role, Status, Email, Password --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="role_user" class="form-label">Role User</label>
                    <select class="form-select" id="role_user" name="role_user" required>
                        <option value="">Pilih</option>
                        <option value="atasan" {{ old('role_user', $karyawan->role_user) == 'atasan' ? 'selected' : '' }}>
                            Atasan</option>
                        <option value="pegawai"
                            {{ old('role_user', $karyawan->role_user) == 'pegawai' ? 'selected' : '' }}>
                            Pegawai</option>
                    </select>
                    <small class="text-danger d-none" id="roleError">Role wajib dipilih</small>
                </div>


                <div class="col-md-4">
                    <label for="status_akun" class="form-label">Status Akun</label>
                    <select class="form-select" id="status_akun" name="status_akun" required>
                        @php
                            $status = old('status_akun', $karyawan->status_akun);
                        @endphp

                        {{-- Tampilkan status asli dari DB --}}
                        <option value="{{ $karyawan->status_akun }}" selected>
                            {{ ucfirst($karyawan->status_akun) }} (default)
                        </option>

                        {{-- Kalau statusnya pengaktifan/aktif maka opsinya penonaktifan --}}
                        @if (in_array($karyawan->status_akun, ['aktif', 'pengaktifan']))
                            <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>
                                Non-Aktifkan
                            </option>
                        @endif

                        {{-- Kalau statusnya penonaktifan maka opsinya pengaktifan --}}
                        @if ($karyawan->status_akun == 'nonaktif')
                            <option value="pengaktifan" {{ $status == 'pengaktifan' ? 'selected' : '' }}>
                                Aktifkan Kembali
                            </option>
                        @endif
                    </select>
                </div>






                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $karyawan->email) }}" required>
                    <small class="text-danger d-none" id="emailError">Format email tidak valid</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- Validasi teks dan tanggal ---
            function toggleError(el, condition) {
                if (condition) el.classList.remove("d-none");
                else el.classList.add("d-none");
            }

            const nama = document.getElementById("nama_lengkap");
            const namaError = document.getElementById("namaError");

            const alamat = document.getElementById("alamat");
            const alamatError = document.getElementById("alamatError");

            const jk = document.getElementById("jenis_kelamin");
            const jkError = document.getElementById("jkError");

            const divisi = document.getElementById("divisi");
            const jabatan = document.getElementById("jabatan");

            const role = document.getElementById("role_user");
            const roleError = document.getElementById("roleError");

            const status = document.getElementById("status_akun");
            const statusError = document.getElementById("statusError");

            const nik = document.getElementById("nik");
            const nikError = document.getElementById("nikError");

            const telp = document.getElementById("no_telp");
            const telpError = document.getElementById("telpError");

            const npwp = document.getElementById("no_npwp");
            const npwpError = document.getElementById("npwpError");

            const email = document.getElementById("email");
            const emailError = document.getElementById("emailError");

            const tglMulai = document.getElementById("tanggal_mulai_kerja");
            const statusKaryawan = document.getElementById("status_karyawan");
            const tglSelesai = document.getElementById("tanggal_selesai_kerja");
            const tglError = document.getElementById("tglError");

            function togglePKWTT() {
                if (statusKaryawan.value === "PKWTT") {
                    tglSelesai.value = "";
                    tglSelesai.setAttribute("disabled", true);
                } else {
                    tglSelesai.removeAttribute("disabled");
                }
            }

            // panggil sekali saat halaman load (untuk kondisi awal)
            togglePKWTT();

            // panggil ulang setiap kali pilihan status berubah
            statusKaryawan.addEventListener("change", togglePKWTT);


            // Nama wajib
            nama.addEventListener("input", function() {
                toggleError(namaError, nama.value.trim() === "");
            });

            // Jenis kelamin wajib
            jk.addEventListener("change", function() {
                toggleError(jkError, jk.value === "");
            });

            // Divisi wajib
            divisi.addEventListener("input", function() {
                if (divisi.value.trim() === "") {
                    divisi.classList.add("is-invalid");
                } else {
                    divisi.classList.remove("is-invalid");
                }
            });

            // Jabatan wajib
            jabatan.addEventListener("input", function() {
                if (jabatan.value.trim() === "") {
                    jabatan.classList.add("is-invalid");
                } else {
                    jabatan.classList.remove("is-invalid");
                }
            });

            // Role wajib
            role.addEventListener("change", function() {
                toggleError(roleError, role.value === "");
            });

            // Status wajib
            status.addEventListener("change", function() {
                toggleError(statusError, status.value === "");
            });

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

            tglMulai.addEventListener('change', () => {
                // Set tanggal minimum di input tanggal selesai sesuai tanggal mulai
                tglSelesai.min = tglMulai.value;

                // Jika tanggal selesai sudah terisi sebelumnya dan < tanggal mulai, reset
                if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                    tglSelesai.value = tglMulai.value;
                }
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
                ["image/jpeg", "image/png"], 2, document.getElementById("ktpError"));

            validateFile(document.getElementById("file_npwp"),
                ["image/jpeg", "image/png"], 2, document.getElementById("npwpFileError"));

            validateFile(document.getElementById("file_sk_kontrak"),
                ["application/pdf"], 2, document.getElementById("skError"));
        });

        document.getElementById('file_sk_kontrak').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type === "application/pdf") {
                const fileURL = URL.createObjectURL(file);
                document.getElementById('pdfPreview').setAttribute('src', fileURL);
                document.getElementById('previewContainer').style.display = "block";
            } else {
                alert("Harap pilih file PDF!");
                document.getElementById('previewContainer').style.display = "none";
            }
        });

        // Preview Gambar (KTP & NPWP)
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const container = document.getElementById(previewId);

            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                container.innerHTML = ""; // hapus preview lama

                if (file) {
                    if (file.type.startsWith("image/")) {
                        const fileURL = URL.createObjectURL(file);
                        container.innerHTML =
                            `<img src="${fileURL}" class="img-thumbnail" style="max-width:200px;">`;
                    } else {
                        alert("Hanya boleh upload file gambar (JPG/PNG)!");
                        event.target.value = ""; // reset input
                    }
                }
            });
        }

        previewImage('file_ktp', 'previewKtpContainer');
        previewImage('file_npwp', 'previewNpwpContainer');
    </script>
@endsection
