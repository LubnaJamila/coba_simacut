<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'no_telp',
        'alamat',
        'jenis_kelamin',
        'no_npwp',
        'tanggal_mulai_kerja',
        'tanggal_selesai_kerja',
        'divisi',
        'jabatan',
        'file_ktp',
        'file_npwp',
        'file_sk_kontrak',
        'role_user',
        'status_akun',
        'email',
        'password',
        'kode_otp',
        'expired_otp',
    ];

    protected $hidden = [
        'password',
        'kode_otp',
    ];

    protected $casts = [
        'expired_otp' => 'datetime',
        'tanggal_mulai_kerja' => 'date',
        'tanggal_selesai_kerja' => 'date',
    ];

    public function isRole($role)
    {
        return $this->role_user === $role;
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_user', 'id_user');
    }

}