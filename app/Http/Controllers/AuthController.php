<?php
namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

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

    // Cari user berdasarkan email
    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    // Cek password cocok atau tidak
    if (!\Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['password' => 'Password yang Anda masukkan salah.']);
    }

    // Login pakai Auth
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // cek status akun
        if ($user->status_akun !== 'Active') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi HRD/yang bersangkutan.']);
        }

        // cek must change password
        if ($user->must_change_password) {
            $request->session()->put('otp_email', $user->email);
            return redirect()->route('reset.form')->with('email', $user->email);
        }

        // redirect per role
        switch ($user->role_user) {
            case 'Superadmin':
                return redirect()->route('dashboard_superadmin');
            case 'HRD':
                return redirect()->route('dashboard_hrd');
            case 'Atasan':
                return redirect()->route('dashboard_atasan');
            case 'Pegawai':
                return redirect()->route('dashboard_pegawai');
            default:
                Auth::logout();
                return back()->withErrors(['email' => 'Role pengguna tidak dikenali.']);
        }
    }

    return back()->withErrors(['email' => 'Terjadi kesalahan saat login.']);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ---------------- FORGOT PASSWORD (OTP) ----------------
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan']);
    }

    // Cek status akun
    if ($user->status_akun !== 'Active') {
        return back()->withErrors(['email' => 'Akun tidak aktif. Silakan hubungi admin.']);
    }

    // Generate OTP
    $otp = rand(1000, 9999);
    $user->otp_code = $otp;
    $user->otp_sent_at = now();
    $user->expired_otp = now()->addMinutes(5);
    $user->save();

    Mail::to($user->email)->send(new OtpMail($user, $otp));

    // Simpan email di session
    $request->session()->put('otp_email', $user->email);

    return redirect()->route('otp.form')->with('status', 'OTP telah dikirim ke email Anda.');
}


    // Tampilkan form OTP
    public function showOtpForm(Request $request)
    {
        $email = $request->session()->get('otp_email'); 
        $sisa = 0;

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user && $user->otp_sent_at) {
                $diff = 60 - Carbon::parse($user->otp_sent_at)->diffInSeconds(now());
                if ($diff > 0) $sisa = $diff;
            }
        }

        return view('auth.otp', compact('email', 'sisa'));
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|array|size:4',
        ]);

        $user = User::where('email', $request->email)->first();

        $sisa = 0;
        if ($user && $user->otp_sent_at) {
            $diff = 60 - Carbon::parse($user->otp_sent_at)->diffInSeconds(now());
            if ($diff > 0) $sisa = $diff;
        }

        if (!$user) {
            return back()->withErrors(['otp' => 'Email tidak ditemukan.'])->with('sisa', $sisa);
        }

        $otpCode = implode('', $request->otp);

        if ($user->otp_code != $otpCode) {
            return back()->withErrors(['otp' => 'Kode OTP salah.'])->with('sisa', $sisa);
        }

        if (Carbon::now()->greaterThan($user->expired_otp)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.'])->with('sisa', $sisa);
        }

        // OTP valid → simpan email di session
        $request->session()->put('otp_email', $user->email);

        return redirect()->route('reset.form');
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('otp_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Email tidak ditemukan.']);
        }

        $otp = rand(1000, 9999);
        $user->otp_code = $otp;
        $user->otp_sent_at = now();
        $user->expired_otp = now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($user, $otp));

        return back()->with('status', 'OTP baru telah dikirim.')->with('sisa', 60);
    }

    // ---------------- RESET PASSWORD (pakai OTP) ----------------
    public function showResetForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        return view('auth.reset', compact('email'));
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ]
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 angka, dan 1 karakter spesial.'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->otp_code = null;
        $user->expired_otp = null;
        $user->password_encrypted = null;
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil direset, silakan login.');
    }

    // ---------------- SET PASSWORD via EMAIL LINK ----------------
    public function showSetPasswordForm(Request $request)
    {
        $token = $request->query('token');

        $user = User::where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', Carbon::now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.set_password', compact('token', 'user'));
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 angka, dan 1 karakter spesial.'
        ]);

        $user = User::where('password_reset_token', $request->token)
                    ->where('password_reset_expires_at', '>', Carbon::now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->password_encrypted = null;
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil diatur. Silakan login.');
    }
}