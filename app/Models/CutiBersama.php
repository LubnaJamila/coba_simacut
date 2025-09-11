<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiBersama extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'cuti_bersama';

    // Primary key
    protected $primaryKey = 'id_cuti_bersama';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_cuti_bersama',
        'tanggal_cuti_bersama',
    ];
}