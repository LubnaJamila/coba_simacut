<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JatahCuti extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'jatah_cuti';

    // Primary key
    protected $primaryKey = 'id_jatah_cuti';

    // Kolom yang bisa diisi
    protected $fillable = [
        'total_cuti_tahunan',
        'total_cuti_tahunan_terpakai',
        'total_cuti_bersama',
        'sisa_cuti_tahunan',
        'id_user',
        'id_jenis_cuti',
    ];

    /**
     * Relasi ke User
     * Satu jatah cuti dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jenisCuti(){
        return $this->belongsTo(JenisCuti::class, 'id_jenis_cuti', 'id_jenis_cuti');
    }
}