<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use App\Models\Pengajuan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;

class RiwayatCutiHRDController extends Controller
{
    public function index(Request $request)
{
     // 🔥 Abaikan semua query string saat refresh (F5)
    if ($request->method() === 'GET' && $request->headers->get('cache-control') === 'max-age=0') {
        $tahun = 'semua';
        $status = 'semua';
        $divisi = 'semua';
    } else {
        $tahun  = $request->input('tahun', 'semua');
        $status = $request->input('status', 'semua');
        $divisi = $request->input('divisi', 'semua');
    }

    // Ambil list tahun dari tanggal cuti
    $listTahun = Pengajuan::select(DB::raw('YEAR(tanggal_mulai_cuti) as tahun'))
        ->union(
            Pengajuan::select(DB::raw('YEAR(tanggal_selesai_cuti) as tahun'))
        )
        ->pluck('tahun')
        ->filter() // buang null
        ->unique()
        ->sort()
        ->values()
        ->prepend('semua');

    // Ambil daftar divisi unik dari user
    $listDivisi = User::select('divisi')
        ->whereNotNull('divisi')
        ->where('divisi', '!=', '')
        ->distinct()
        ->pluck('divisi')
        ->prepend('semua');

    $query = Pengajuan::with('user');

    // ✅ Filter Tahun (dibungkus function biar OR tetap dalam 1 grup)
    if ($tahun !== 'semua') {
        $query->where(function ($q) use ($tahun) {
            $q->whereYear('tanggal_mulai_cuti', $tahun)
              ->orWhereYear('tanggal_selesai_cuti', $tahun);
        });
    }

    // ✅ Filter Status (langsung di tabel pengajuan)
    if ($status !== 'semua') {
        $query->where('status_pengajuan', $status);
    }

    // ✅ Filter Divisi (join ke tabel user)
    if ($divisi !== 'semua') {
        $query->whereHas('user', function ($q) use ($divisi) {
            $q->where('divisi', $divisi);
        });
    }

    $cuti = $query->get();

    return view('hrd.riwayat_cuti_hrd', compact(
        'cuti',
        'tahun',
        'status',
        'divisi',
        'listTahun',
        'listDivisi'
    ));
}


public function exportPdf(Request $request)
{
    $status = $request->input('status', 'semua');
    $divisi = $request->input('divisi', 'semua');
    $tahun  = $request->input('tahun', 'semua');

    $query = Pengajuan::with(['user', 'jenisCuti']);

    if ($tahun !== 'semua') {
        $query->whereYear('tanggal_mulai_cuti', $tahun)
              ->orWhereYear('tanggal_selesai_cuti', $tahun);
    }

    if ($status !== 'semua') {
        $query->where('status_pengajuan', $status);
    }

    if ($divisi !== 'semua') {
        $query->whereHas('user', fn($q) => $q->where('divisi', $divisi));
    }

    $cuti = $query->get();

    // Generate PDF
    $pdf = Pdf::loadView('hrd.pdf', compact('cuti', 'tahun', 'status', 'divisi'))
                ->setPaper('a4', 'landscape');   

    return $pdf->download('riwayat_pengajuan_cuti.pdf');
}

}