<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->increments('id_pengajuan');
            $table->date('tanggal_mulai_cuti');
            $table->date('tanggal_selesai_cuti');
            $table->integer('lama_cuti');
            $table->integer('sisa_cuti_riwayat')->nullable();
            $table->text('task_dikerjakan');
            $table->string('task_dialihkan');
            $table->text('alasan_cuti')->nullable();
            $table->string('lampiran_pendukung_file')->nullable();
            $table->enum('status_pengajuan', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('alasan_keterangan_approval')->nullable();
            $table->unsignedInteger('id_jenis_cuti');
            $table->foreign('id_jenis_cuti')->references('id_jenis_cuti')->on('jenis_cuti')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};