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
    public $incrementing = true;
    protected $keyType = 'int';

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
        'status_karyawan',
        'file_ktp',
        'file_npwp',
        'file_sk_kontrak',
        'role_user',
        'status_akun',
        'email',
        'password',
        'password_encrypted',
        'must_change_password',
        'password_reset_token',
        'password_reset_expires_at',
        'kode_otp',
        'expired_otp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'password_encrypted',
        'password_reset_token',
        'kode_otp',
    ];

    protected $casts = [
        'must_change_password' => 'boolean',
        'password_reset_expires_at' => 'datetime',
        'expired_otp' => 'datetime',
        'tanggal_mulai_kerja' => 'date',
        'tanggal_selesai_kerja' => 'date',
    ];

    public function isRole($role)
    {
        return $this->role_user === $role;
    }

    public function jatahCuti()
    {
        return $this->hasMany(JatahCuti::class, 'id_user', 'id_user');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'id_user', 'id_user');
    }

}