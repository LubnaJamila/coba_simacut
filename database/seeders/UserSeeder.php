<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'HRD',
            'email' => 'hrdtest@gmail.com',
            'password' => Hash::make('123456'), // password di-hash
            'role_user' => 'hrd',
            'status_akun' => 'aktif', // misalnya ada kolom status
        ]);

        User::create([
            'nama_lengkap' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'role_user' => 'superadmin',
            'status_akun' => 'aktif',
        ]);
    }
}