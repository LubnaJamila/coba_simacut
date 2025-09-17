<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->Increments('id_user');

            // Identitas
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
            $table->enum('status_karyawan', ['Probation', 'PKWT', 'PKWTT']);

            // File Upload
            $table->string('file_ktp')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_sk_kontrak')->nullable();

            // Akun
            $table->enum('role_user', ['Superadmin', 'Atasan', 'HRD', 'Pegawai']);
            $table->enum('status_akun', ['Waiting-Activation', 'Active', 'Non-Active'])->default('Waiting-Activation');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('password_encrypted')->nullable();
            $table->boolean('must_change_password')->default(true);
            
            // Reset Password
            $table->string('password_reset_token', 100)->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->timestamp('expired_otp')->nullable();
            
            // Laravel default
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};