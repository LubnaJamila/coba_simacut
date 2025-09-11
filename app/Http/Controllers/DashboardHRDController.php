<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardHRDController extends Controller
{
    public function index(){
        $user = auth()->user();
        return view('hrd.dashboard_hrd', compact('user'));
    }
}