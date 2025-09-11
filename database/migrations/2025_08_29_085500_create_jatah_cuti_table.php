<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function PHPUnit\Framework\once;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jatah_cuti', function (Blueprint $table) {
            $table->increments('id_jatah_cuti');
            $table->integer('total_cuti_tahunan')->nullable();
            $table->integer('total_cuti_tahunan_terpakai')->nullable();
            $table->integer('total_cuti_bersama')->nullable();
            $table->integer('sisa_cuti_tahunan')->nullable();
            $table->unsignedInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('id_jenis_cuti');
            $table->foreign('id_jenis_cuti')->references('id_jenis_cuti')->on('jenis_cuti')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jatah_cuti');
    }
};