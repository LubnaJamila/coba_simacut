<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPegawaiController extends Controller
{
    public function index(){
        $user = auth()->user();
        return view('pegawai.dashboard_pegawai', compact('user'));
    }
}