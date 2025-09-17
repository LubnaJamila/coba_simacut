<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DataAkunController extends Controller
{
    public function index(Request $request)
{
    $status_akun = $request->get('status_akun', 'Active'); // default Active

    $karyawans = User::whereIn('role_user', ['Atasan', 'Pegawai']) // hanya Atasan & Pegawai
        ->when($status_akun == 'Active', function ($query) {
            $query->where('status_akun', 'Active');
        })
        ->when($status_akun == 'Non-Active', function ($query) {
            $query->where('status_akun', 'Non-Active');
        })
        ->get();

    return view('superadmin.data_akun', compact('karyawans', 'status_akun'));
}

}