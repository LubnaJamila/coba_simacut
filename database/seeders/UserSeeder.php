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
            'email' => 'lubnajamila062@gmail.com',
            'password' => Hash::make('123456'), // password di-hash
            'role_user' => 'HRD',
            'status_akun' => 'Active',
            'must_change_password' => false,
        ]);

        User::create([
            'nama_lengkap' => 'Super Admin',
            'email' => 'lubnajamila475@gmail.com',
            'password' => Hash::make('123456'),
            'role_user' => 'Superadmin',
            'status_akun' => 'Active',
            'must_change_password' => false,
        ]);
    }
}