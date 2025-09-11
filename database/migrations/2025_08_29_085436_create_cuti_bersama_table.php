<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cuti_bersama', function (Blueprint $table) {
            $table->increments('id_cuti_bersama');
            $table->string('nama_cuti_bersama');
            $table->date('tanggal_cuti_bersama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti_bersama');
    }
};