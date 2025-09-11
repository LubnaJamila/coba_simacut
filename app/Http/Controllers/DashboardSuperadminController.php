<?php

namespace App\Http\Controllers;

use App\Models\CutiBersama;
use App\Models\JatahCuti;
use App\Models\JenisCuti;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class DashboardSuperadminController extends Controller
{
    public function index(){
        $user = auth()->user();
        $karyawan = User::where('status_akun', 'pengaktifan')->get();
        return view('superadmin.dashboard_superadmin', compact('user', 'karyawan'));
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
    // Cari user
    $user = User::findOrFail($id_user);

    // Aktifkan akun
    $user->status_akun = 'aktif';
    $user->save();

    // Ambil semua aturan cuti tahunan dari tabel jenis_cuti (urut per tahun)
    $aturanTahunan = JenisCuti::where('jenis_cuti', 'Tahunan')
        ->orderBy('tahun', 'asc')
        ->get();

    if ($aturanTahunan->isEmpty()) {
        return redirect()->route('dashboard_superadmin')
            ->with('success', 'Akun berhasil diaktifkan. Belum ada aturan cuti tahunan yang tersedia.');
    }

    $sisaTahunLalu = 0;

    foreach ($aturanTahunan as $jenisTahunan) {
        $year  = $jenisTahunan->tahun;
        $kuota = $jenisTahunan->kuota;

        // Cek apakah sudah ada record jatah cuti untuk user ini & tahun ini
        $jatahCuti = JatahCuti::where('id_user', $id_user)
            ->where('id_jenis_cuti', $jenisTahunan->id_jenis_cuti)
            ->first();

        // Hitung total cuti bersama tahun ini
        $totalCutiBersama = CutiBersama::whereYear('tanggal_cuti_bersama', $year)->count();

        // Hitung total cuti tahunan terpakai tahun ini
        $totalTerpakai = $user->pengajuan()
            ->whereHas('jenisCuti', fn($q) => $q->where('jenis_cuti', 'Tahunan'))
            ->where('status_pengajuan', 'diterima')
            ->where(function ($q) use ($year) {
                $q->whereYear('tanggal_mulai_cuti', $year)
                  ->orWhereYear('tanggal_selesai_cuti', $year);
            })
            ->sum('lama_cuti');

        // Hak cuti tahun ini = sisa tahun lalu + kuota tahun ini
        $totalHakCuti = $sisaTahunLalu + $kuota;

        // Hitung sisa cuti
        $sisaCuti = $totalHakCuti - ($totalTerpakai + $totalCutiBersama);

        if ($jatahCuti) {
            // Jika sudah ada → update
            $jatahCuti->update([
                'total_cuti_tahunan'          => $totalHakCuti,
                'total_cuti_tahunan_terpakai' => $totalTerpakai,
                'total_cuti_bersama'          => $totalCutiBersama,
                'sisa_cuti_tahunan'           => $sisaCuti,
            ]);
        } else {
            // Jika belum ada → buat baru
            JatahCuti::create([
                'id_user'                     => $id_user,
                'id_jenis_cuti'               => $jenisTahunan->id_jenis_cuti,
                'total_cuti_tahunan'          => $totalHakCuti,
                'total_cuti_tahunan_terpakai' => $totalTerpakai,
                'total_cuti_bersama'          => $totalCutiBersama,
                'sisa_cuti_tahunan'           => $sisaCuti,
            ]);
        }

        // Simpan sisa tahun ini → untuk dialokasikan ke tahun berikutnya
        $sisaTahunLalu = $sisaCuti;
    }

    return redirect()->route('dashboard_superadmin')
        ->with('success', 'Akun berhasil diaktifkan. Semua jatah cuti tahunan diperbarui hingga tahun-tahun berikutnya.');
}









}