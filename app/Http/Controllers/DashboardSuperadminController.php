<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivatedMail;
use App\Mail\AccountReactivatedMail;
use App\Models\CutiBersama;
use App\Models\JatahCuti;
use App\Models\JenisCuti;
use App\Models\User;
use Carbon\Carbon;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Mail;
use Str;

class DashboardSuperadminController extends Controller
{
    public function index(){
        $user = auth()->user();
        // Ambil semua user dengan role pegawai atau atasan
        $karyawan = User::whereIn('role_user', ['pegawai', 'atasan'])
                        ->where('status_akun', 'Waiting-Activation')
                        ->get();

        // Hitung total berdasarkan status_akun hanya untuk role_user pegawai dan atasan
        $totalActive = User::whereIn('role_user', ['pegawai', 'atasan'])
                           ->where('status_akun', 'Active')
                           ->count();

        $totalWaiting = User::whereIn('role_user', ['pegawai', 'atasan'])
                            ->where('status_akun', 'Waiting-Activation')
                            ->count();

        $totalNonActive = User::whereIn('role_user', ['pegawai', 'atasan'])
                              ->where('status_akun', 'Non-Active')
                              ->count();

        return view('superadmin.dashboard_superadmin', compact('user', 'karyawan', 
            'totalActive', 
            'totalWaiting', 
            'totalNonActive'));
    }
    public function show($id_user)
    {
        // ambil data karyawan berdasarkan ID
        $karyawan = User::findOrFail($id_user);

        // lempar ke blade detail
        return view('superadmin.detail_pengajuan_akun', compact('karyawan'));
    }

    public function setujuiAktifkanAkun($id_user)
{
    $user = User::findOrFail($id_user);

    // Cek apakah user lama (email sudah ada di user lain)
    $existingUser = User::where('email', $user->email)
                        ->where('id_user', '!=', $user->id_user)
                        ->first();

    $isNewUser = $existingUser ? false : true;

    // Aktifkan akun
    $user->status_akun = 'Active';

    // --- Update jatah cuti tahunan (HANYA untuk pegawai) ---
    if ($user->role_user === 'Pegawai') {
        $aturanTahunan = JenisCuti::where('jenis_cuti', 'Tahunan')
            ->orderBy('tahun', 'asc')
            ->get();

        $sisaTahunLalu = 0;

        foreach ($aturanTahunan as $jenisTahunan) {
            $year  = $jenisTahunan->tahun;
            $kuota = $jenisTahunan->kuota;

            $jatahCuti = JatahCuti::where('id_user', $id_user)
                ->where('id_jenis_cuti', $jenisTahunan->id_jenis_cuti)
                ->first();

            $totalCutiBersama = CutiBersama::whereYear('tanggal_cuti_bersama', $year)->count();

            $totalTerpakai = $user->pengajuan()
                ->whereHas('jenisCuti', fn($q) => $q->where('jenis_cuti', 'Tahunan'))
                ->where('status_pengajuan', 'diterima')
                ->where(function ($q) use ($year) {
                    $q->whereYear('tanggal_mulai_cuti', $year)
                      ->orWhereYear('tanggal_selesai_cuti', $year);
                })
                ->sum('lama_cuti');

            $totalHakCuti = $sisaTahunLalu + $kuota;
            $sisaCuti = $totalHakCuti - ($totalTerpakai + $totalCutiBersama);

            if ($jatahCuti) {
                $jatahCuti->update([
                    'total_cuti_tahunan'          => $totalHakCuti,
                    'total_cuti_tahunan_terpakai' => $totalTerpakai,
                    'total_cuti_bersama'          => $totalCutiBersama,
                    'sisa_cuti_tahunan'           => $sisaCuti,
                ]);
            } else {
                JatahCuti::create([
                    'id_user'                     => $id_user,
                    'id_jenis_cuti'               => $jenisTahunan->id_jenis_cuti,
                    'total_cuti_tahunan'          => $totalHakCuti,
                    'total_cuti_tahunan_terpakai' => $totalTerpakai,
                    'total_cuti_bersama'          => $totalCutiBersama,
                    'sisa_cuti_tahunan'           => $sisaCuti,
                ]);
            }

            $sisaTahunLalu = $sisaCuti;
        }
    }

    // --- Kirim email sesuai tipe user ---
    if ($isNewUser && $user->password_encrypted) {
        // 🔑 User baru → kasih password sementara + link set password
        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->password_reset_expires_at = Carbon::now('Asia/Jakarta')->addHours(24);

        $passwordTemporary = Crypt::decryptString($user->password_encrypted);
        $changePasswordUrl = url('/set-password?token=' . $token);

        Mail::to($user->email)->send(new AccountActivatedMail($user, $passwordTemporary, $changePasswordUrl));
    } else {
        // 🔑 User lama → tetap login dengan password lama
        Mail::to($user->email)->send(new AccountReactivatedMail($user));
    }

    $user->save();

    return redirect()->route('dashboard_superadmin')
        ->with('success', 'Akun berhasil diaktifkan dan pegawai telah diberi notifikasi.');
}










}