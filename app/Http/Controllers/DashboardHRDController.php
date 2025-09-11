<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use App\Models\Pengajuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardHRDController extends Controller
{
    public function index(Request $request)
{
    $tahunCutiTahunanList = JenisCuti::select('tahun')
                            ->distinct()
                            ->orderBy('tahun','asc')
                            ->pluck('tahun');

    $divisiList = User::where('role_user', 'pegawai')
                            ->select('divisi')
                            ->distinct()
                            ->pluck('divisi');

    $tahun = $request->get('tahun', Carbon::now()->year);
    $divisi = $request->get('divisi', null);

    $karyawanQuery = User::whereIn('role_user', ['pegawai', 'atasan']);
    if ($divisi) {
        $karyawanQuery->where('divisi', $divisi);
    }
    $karyawan = $karyawanQuery->get();

    // Cari id jenis cuti tahunan & non-tahunan
    $jenisTahunan = JenisCuti::where('jenis_cuti', 'Tahunan')->pluck('id_jenis_cuti');
    $jenisNonTahunan = JenisCuti::where('jenis_cuti', 'Non-Tahunan')->pluck('id_jenis_cuti');

    $data = $karyawan->map(function ($user) use ($tahun, $jenisTahunan, $jenisNonTahunan) {
        $cuti = Pengajuan::where('id_user', $user->id_user)
            ->whereYear('tanggal_mulai_cuti', $tahun)
            ->where('status_pengajuan', 'diterima')
            ->get();

        $totalTahunan = $cuti->whereIn('id_jenis_cuti', $jenisTahunan)->sum('lama_cuti');
        $totalNonTahunan = $cuti->whereIn('id_jenis_cuti', $jenisNonTahunan)->sum('lama_cuti');

        $blockLeave = false;
        $periode = null;

        foreach ($cuti->whereIn('id_jenis_cuti', $jenisTahunan) as $ct) {
            $lama = $ct->lama_cuti;
            if ($lama >= 3) {
                $blockLeave = true;
                $periode = Carbon::parse($ct->tanggal_mulai_cuti)->format('d/m/Y') .
                    ' - ' . Carbon::parse($ct->tanggal_selesai_cuti)->format('d/m/Y');
                break;
            }
        }

        return [
            'id_user' => $user->id_user,
            'nama' => $user->nama_lengkap,
            'divisi' => $user->divisi,
            'cuti_tahunan' => $totalTahunan,
            'cuti_non_tahunan' => $totalNonTahunan,
            'block_leave' => $blockLeave,
            'periode_block_leave' => $periode,
        ];
    });

    // ======================
    // 🔹 Ringkasan Dashboard
    // ======================

    // Total karyawan
    $totalAktif = User::whereIn('role_user', ['pegawai', 'atasan'])
                    ->where('status_akun', 'aktif')
                    ->count();
    $totalNonAktif = User::whereIn('role_user', ['pegawai', 'atasan'])
                    ->where('status_akun', 'non-aktif')
                    ->count();

    // Pengajuan cuti
    $totalDiterima = Pengajuan::whereYear('tanggal_mulai_cuti', $tahun)
                        ->where('status_pengajuan', 'diterima')
                        ->count();
    $totalDitolak = Pengajuan::whereYear('tanggal_mulai_cuti', $tahun)
                        ->where('status_pengajuan', 'ditolak')
                        ->count();

    // Block leave
    $sudahAmbil = $data->where('block_leave', true)->count();
    $belumAmbil = $data->where('block_leave', false)->count();

    $summary = [
        'karyawan' => [
            'aktif' => $totalAktif,
            'nonaktif' => $totalNonAktif,
        ],
        'pengajuan' => [
            'diterima' => $totalDiterima,
            'ditolak' => $totalDitolak,
        ],
        'block_leave' => [
            'sudah' => $sudahAmbil,
            'belum' => $belumAmbil,
        ],
    ];

    return view('hrd.dashboard_hrd', compact(
        'data', 'tahun', 'tahunCutiTahunanList',
        'divisi', 'divisiList', 'summary'
    ));
}

}