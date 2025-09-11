<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pengajuan';

    // Primary key
    protected $primaryKey = 'id_pengajuan';

    // Kolom yang bisa diisi
    protected $fillable = [
        'tanggal_mulai_cuti',
        'tanggal_selesai_cuti',
        'lama_cuti',
        'sisa_cuti_riwayat',
        'task_dikerjakan',
        'task_dialihkan',
        'alasan_cuti',
        'lampiran_pendukung_file',
        'status_pengajuan',
        'alasan_keterangan_approval',
        'id_jenis_cuti',
        'id_user',
    ];

    /**
     * Relasi ke JenisCuti
     * Satu pengajuan cuti memiliki satu jenis cuti
     */
    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class, 'id_jenis_cuti', 'id_jenis_cuti');
    }

    /**
     * Relasi ke User
     * Satu pengajuan cuti diajukan oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}