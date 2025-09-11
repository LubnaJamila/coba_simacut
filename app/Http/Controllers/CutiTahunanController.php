<?php

namespace App\Http\Controllers;

use App\Models\CutiBersama;
use App\Models\JatahCuti;
use App\Models\JenisCuti;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CutiTahunanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tahunSekarang = date('Y');

        $tahunCutiTahunanList = JenisCuti::select('tahun')
    ->distinct()
    ->orderBy('tahun','asc')
    ->pluck('tahun');
    
// Ambil data jatah cuti tahun ini
        // $jatahCuti = \App\Models\JatahCuti::where('tahun', $tahunSekarang)->first();
        // Ambil jenis cuti Tahunan yang dibuat atau diupdate di tahun sekarang
        $cutiTahunan = JenisCuti::where('jenis_cuti', 'Tahunan')
            ->where(function($query) use ($tahunSekarang) {
                $query->whereYear('updated_at', $tahunSekarang);
            })
            ->first();
            $kuotaTahunan = $cutiTahunan ? $cutiTahunan->kuota : 0;
        // Ambil total cuti bersama di tahun sekarang
        $totalCutiBersama = \App\Models\CutiBersama::whereYear('tanggal_cuti_bersama', $tahunSekarang)->count();

        // Hitung jatah cuti tahunan yang bisa diambil
        $jatahCutiTahunan = 0;
        if ($kuotaTahunan) {
            $jatahCutiTahunan = max(0, $kuotaTahunan - $totalCutiBersama);
        }

        $cutiBersamaList = CutiBersama::select('id_cuti_bersama','tanggal_cuti_bersama','nama_cuti_bersama')
            ->whereYear('tanggal_cuti_bersama', $tahunSekarang)
            ->get();

        // ✅ Cek apakah ada pegawai aktif
        $adaPegawaiAktif = \App\Models\User::where('role_user', 'pegawai')
                            ->where('status_akun', 'aktif')
                            ->exists();

        return view('hrd.cuti_tahunan', compact(
            'user',
            'tahunSekarang',
            'cutiBersamaList',
            'jatahCutiTahunan',
            'totalCutiBersama',
            'adaPegawaiAktif',
            'tahunCutiTahunanList' // lempar ke view
        ));
}


    public function store(Request $request)
{
    $request->validate([
        'nama_cuti' => 'required|string',
        'kuota'     => 'required|integer|min:1',
        'tahun'     => 'required|integer'
    ]);

    $namaCuti = $request->nama_cuti;
    $kuota    = $request->kuota;
    $tahun    = $request->tahun;

    DB::transaction(function () use ($namaCuti, $kuota, $tahun) {
        // 1. Simpan aturan cuti tahunan untuk tahun ini di tabel jenis_cuti
        $jenisCuti = JenisCuti::updateOrCreate(
            ['nama_cuti' => $namaCuti, 'tahun' => $tahun], // unique per nama + tahun
            [
                'kuota'      => $kuota,
                'jenis_cuti' => 'Tahunan'
            ]
        );

        // 2. Ambil semua pegawai aktif
        $users = User::where('role_user', 'pegawai')
                     ->where('status_akun', 'aktif')
                     ->get();

        // 3. Hitung total cuti bersama di tahun ini
        $totalCutiBersama = CutiBersama::whereYear('tanggal_cuti_bersama', $tahun)->count();

        foreach ($users as $user) {
            // 4. Hitung cuti terpakai tahun ini
            $totalTerpakai = Pengajuan::where('id_user', $user->id_user)
                ->where('status_pengajuan', 'diterima')
                ->where(function($q) use ($tahun) {
                    $q->whereYear('tanggal_mulai_cuti', $tahun)
                      ->orWhereYear('tanggal_selesai_cuti', $tahun);
                })
                ->whereHas('jenisCuti', function($query) {
                    $query->where('jenis_cuti', 'Tahunan');
                })
                ->sum('lama_cuti') ?? 0;

            // 5. Cari aturan tahun sebelumnya dari tabel jenis_cuti
            $jenisSebelumnya = JenisCuti::where('nama_cuti', $namaCuti)
                ->where('tahun', $tahun - 1)
                ->first();

            $carryOver = 0;
            if ($jenisSebelumnya) {
                $jatahSebelumnya = JatahCuti::where('id_user', $user->id_user)
                    ->where('id_jenis_cuti', $jenisSebelumnya->id_jenis_cuti)
                    ->first();

                if ($jatahSebelumnya) {
                    $carryOver = $jatahSebelumnya->sisa_cuti_tahunan;
                }
            }

            // 6. Hitung kuota tahun baru (kuota aturan + carry over)
            $totalKuota = $kuota + $carryOver;

            // 7. Hitung sisa cuti tahun ini (boleh minus)
            $sisaCuti = $totalKuota - $totalCutiBersama - $totalTerpakai;

            // 8. Insert/Update jatah cuti pegawai
            JatahCuti::updateOrCreate(
                [
                    'id_user'      => $user->id_user,
                    'id_jenis_cuti'=> $jenisCuti->id_jenis_cuti, // relasi ke aturan cuti
                ],
                [
                    'total_cuti_tahunan'          => $totalKuota,
                    'total_cuti_bersama'          => $totalCutiBersama,
                    'total_cuti_tahunan_terpakai' => $totalTerpakai,
                    'sisa_cuti_tahunan'           => $sisaCuti,
                ]
            );
        }
    });

    return back()->with('success', "Jatah cuti $namaCuti ($tahun) berhasil ditambahkan untuk semua pegawai aktif.");
}





}