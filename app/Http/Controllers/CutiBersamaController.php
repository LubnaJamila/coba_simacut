<?php

namespace App\Http\Controllers;

use App\Models\CutiBersama;
use App\Models\JatahCuti;
use App\Models\JenisCuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CutiBersamaController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();

        // ambil tahun yang dipilih, default tahun sekarang
        $year = $request->input('year', date('Y'));

        // ambil semua cuti bersama di tahun tsb
        $cutiBersamaList = CutiBersama::select('tanggal_cuti_bersama','nama_cuti_bersama')
            ->whereYear('tanggal_cuti_bersama', $year)
            ->get();

        $tahunCutiTahunanList = JenisCuti::select('tahun')
                                            ->distinct()
                                            ->orderBy('tahun','asc')
                                            ->pluck('tahun');
                                            
        // ambil tahun cuti terakhir (batas maksimal tahun input cuti bersama)
        $tahunCutiTahunan = $tahunCutiTahunanList->max();

        // jika user pilih tahun > tahun cuti tahunan terakhir → kunci jadi tahun terakhir
        if ($year > $tahunCutiTahunan) {
            $year = $tahunCutiTahunan;
        }

        return view('hrd.cuti_bersama', compact(
            'cutiBersamaList',
            'year',
            'tahunCutiTahunan',
            'tahunCutiTahunanList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_cuti_bersama' => 'required|date',
            'nama_cuti_bersama'    => 'nullable|string',
        ]);

        $cutiBersama = CutiBersama::create([
            'tanggal_cuti_bersama' => $request->tanggal_cuti_bersama,
            'nama_cuti_bersama'    => $request->nama_cuti_bersama,
        ]);

        // Ambil tahun dari tanggal cuti bersama
        $year = date('Y', strtotime($request->tanggal_cuti_bersama));
        $this->cascadeUpdateJatahCuti($year);

        return redirect()->back()->with('success', 'Cuti bersama berhasil ditambahkan.');
    }

    public function update(Request $request, $id_cuti_bersama)
    {
        $request->validate([
            'tanggal_cuti_bersama' => 'required|date',
            'nama_cuti_bersama'    => 'required|string|max:255',
        ]);

        $cutiBersama = CutiBersama::findOrFail($id_cuti_bersama);

        $oldYear = date('Y', strtotime($cutiBersama->tanggal_cuti_bersama));
        $newYear = date('Y', strtotime($request->tanggal_cuti_bersama));

        $cutiBersama->update([
            'tanggal_cuti_bersama' => $request->tanggal_cuti_bersama,
            'nama_cuti_bersama'    => $request->nama_cuti_bersama,
        ]);

        // Update dari tahun terkecil sampai ke depan
        $startYear = min($oldYear, $newYear);
        $this->cascadeUpdateJatahCuti($startYear);

        return back()->with('success', 'Cuti bersama berhasil diperbarui.');
    }

    public function destroy($id_cuti_bersama)
    {
        $cutiBersama = CutiBersama::findOrFail($id_cuti_bersama);
        $year = date('Y', strtotime($cutiBersama->tanggal_cuti_bersama));
        $cutiBersama->delete();

        // Cascade update setelah hapus
        $this->cascadeUpdateJatahCuti($year);

        return back()->with('success', 'Cuti bersama berhasil dihapus.');
    }

    /**
     * Update jatah cuti mulai dari $year sampai ke depan
     */
    private function cascadeUpdateJatahCuti($year)
    {
        // Cari semua tahun yang ada di tabel jenis_cuti mulai dari $year ke depan
        $tahunList = JenisCuti::where('jenis_cuti', 'Tahunan')
            ->where('tahun', '>=', $year)
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();

        foreach ($tahunList as $tahun) {
            $this->updateJatahCutiByYear($tahun);
        }
    }

    private function updateJatahCutiByYear($year)
    {
        $jenisTahunan = JenisCuti::where('jenis_cuti', 'Tahunan')
            ->where('tahun', $year)
            ->first();

        if (!$jenisTahunan) {
            return;
        }

        $kuotaTahunIni = $jenisTahunan->kuota;
        $totalCutiBersama = CutiBersama::whereYear('tanggal_cuti_bersama', $year)->count();

        $users = User::where('role_user', 'pegawai')
            ->where('status_akun', 'aktif')
            ->get();

        foreach ($users as $user) {
            $previousYear = $year - 1;
            $sisaTahunLalu = 0;

            $previousJenis = JenisCuti::where('jenis_cuti', 'Tahunan')
                ->where('tahun', $previousYear)
                ->first();

            if ($previousJenis) {
                $previousJatah = JatahCuti::where('id_user', $user->id_user)
                    ->where('id_jenis_cuti', $previousJenis->id_jenis_cuti)
                    ->first();

                if ($previousJatah) {
                    $sisaTahunLalu = max(0, $previousJatah->sisa_cuti_tahunan); 
                }
            }

            $totalTerpakai = $user->pengajuan()
                ->whereHas('jenisCuti', fn($q) => $q->where('jenis_cuti', 'Tahunan'))
                ->where('status_pengajuan', 'diterima')
                ->where(function ($q) use ($year) {
                    $q->whereYear('tanggal_mulai_cuti', $year)
                    ->orWhereYear('tanggal_selesai_cuti', $year);
                })
                ->sum('lama_cuti');

            $totalHakCuti = $sisaTahunLalu + $kuotaTahunIni;
            $sisaCuti = $totalHakCuti - ($totalTerpakai + $totalCutiBersama);

            JatahCuti::updateOrCreate(
                [
                    'id_user'       => $user->id_user,
                    'id_jenis_cuti' => $jenisTahunan->id_jenis_cuti,
                ],
                [
                    'total_cuti_tahunan'          => $totalHakCuti,
                    'total_cuti_tahunan_terpakai' => $totalTerpakai,
                    'total_cuti_bersama'          => $totalCutiBersama,
                    'sisa_cuti_tahunan'           => $sisaCuti,
                ]
            );
        }
    }
}