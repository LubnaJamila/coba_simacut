<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->Increments('id_user');
            $table->string('nama_lengkap');
            $table->string('nik')->unique()->nullable();
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->string('no_npwp')->unique()->nullable();
            $table->date('tanggal_mulai_kerja')->nullable();
            $table->date('tanggal_selesai_kerja')->nullable();
            $table->string('divisi')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('file_ktp')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_sk_kontrak')->nullable();
            $table->enum('role_user', ['superadmin', 'atasan', 'hrd', 'pegawai']);
            $table->enum('status_akun', ['pengaktifan', 'aktif', 'penonaktifan', 'nonaktif'])->default('pengaktifan');
            $table->enum('status_karyawan', ['Probation', 'PKWT', 'PKWTT']);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('kode_otp')->nullable();
            $table->dateTime('expired_otp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};