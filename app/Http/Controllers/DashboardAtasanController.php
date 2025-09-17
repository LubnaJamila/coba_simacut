<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAtasanController extends Controller
{
    public function index(){
        $user = auth()->user();
        return view('atasan.dashboard_atasan', compact('user'));
    }
}