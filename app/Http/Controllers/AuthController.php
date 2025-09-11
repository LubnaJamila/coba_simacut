<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        // ✅ cek dulu email + password
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ✅ baru cek status akun
            if ($user->status_akun !== 'aktif') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum/tidak aktif. Silakan hubungi HRD anda.'
                ])->onlyInput('email');
            }

            // ✅ Role-based redirect
            if ($user->role_user == 'superadmin') {
                return redirect()->intended('dashboard_superadmin');
            } elseif ($user->role_user == 'hrd') {
                return redirect()->intended('dashboard_hrd');
            } elseif ($user->role_user == 'atasan') {
                return redirect()->intended('dashboard_atasan');
            } elseif ($user->role_user == 'pegawai') {
                return redirect()->intended('dashboard_pegawai');
            }
        }

        // ✅ kalau Auth::attempt gagal → email atau password salah
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah, mohon login kembali.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // public function dashboard()
    // {
    //     $user = auth()->user();
    //     return view('dashboard', compact('user'));
    // }
}