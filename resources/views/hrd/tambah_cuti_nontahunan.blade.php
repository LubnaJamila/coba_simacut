@extends('layouts.app')

@section('content')
    <style>
        .cuti-form {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }
    </style>
    <h2>Selamat datang, {{ $user->nama_lengkap }}!</h2>
    <p>Ini halaman Tambah Cuti Non Tahunan, menu di sidebar menyesuaikan role user.</p>

    <div class="container mt-4">
        <h2>Tambah Jenis Cuti</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('jenis_cuti.store') }}">
            @csrf
            <button id="addForm" type="button" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Tambah Cuti
            </button>

            <div id="cutiContainer"></div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-dark">Simpan</button>
            </div>
        </form>
    </div>
    <script>
        let counter = 0;

        function addCutiForm() {
            counter++;
            const container = document.getElementById('cutiContainer');

            const formGroup = document.createElement('div');
            formGroup.classList.add('row', 'align-items-center', 'cuti-form', 'mb-2');
            formGroup.setAttribute('id', `form-${counter}`);

            formGroup.innerHTML = `
            <div class="row mb-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label">Nama Cuti</label>
                <input type="text" name="jenis_cuti[]" class="form-control" placeholder="Nama Cuti" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Kuota Hari</label>
                <input type="text" name="kuota[]" class="form-control" placeholder="Misal: 10" required>
            </div>
            <div class="col-md-2 text-end d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-remove mt-2">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="col-md-5">
                <label class="form-label">Tipe Penggunaan</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipe_penggunaan_${counter}" value="sekali" required>
                    <label class="form-check-label">Sekali</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipe_penggunaan_${counter}" value="berkali-kali">
                    <label class="form-check-label">Berkali - kali</label>
                </div>
            </div>
            <div class="col-md-5">
                <label class="form-label">Syarat</label>
                <textarea name="syarat[]" class="form-control" placeholder="Misal: 10" rows="3" required></textarea>
            </div>
        </div>
    `;

            container.appendChild(formGroup);

            // remove button
            formGroup.querySelector('.btn-remove').addEventListener('click', function() {
                formGroup.remove();
            });
        }

        // form awal langsung muncul
        addCutiForm();

        document.getElementById('addForm').addEventListener('click', addCutiForm);
    </script>
@endsection
