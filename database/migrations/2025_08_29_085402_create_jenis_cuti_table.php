<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_cuti', function (Blueprint $table) {
            $table->increments('id_jenis_cuti');
            $table->string('nama_cuti');
            $table->integer('kuota')->nullable();
            $table->year('tahun')->nullable();
            $table->enum('jenis_cuti', ['Tahunan', 'Non-Tahunan']);
            $table->enum('tipe_penggunaan', ['sekali', 'berkali-kali'])->nullable();
            $table->text('syarat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_cuti');
    }
};