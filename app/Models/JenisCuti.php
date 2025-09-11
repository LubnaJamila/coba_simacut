<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;

    // Nama tabel (karena tidak mengikuti default plural "jenis_cutis")
    protected $table = 'jenis_cuti';

    // Primary key
    protected $primaryKey = 'id_jenis_cuti';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_cuti',
        'kuota',
        'tahun',
        'jenis_cuti',
        'tipe_penggunaan',
        'syarat',
    ];
}