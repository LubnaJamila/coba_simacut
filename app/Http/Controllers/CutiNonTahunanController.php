<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use Auth;
use DB;
use Illuminate\Http\Request;

class CutiNonTahunanController extends Controller
{
    public function index(){
        $user = auth()->user();
        $cutiNonTahunan = JenisCuti::where('jenis_cuti', 'Non-Tahunan')->get();
        $totalCutiNontahunan = JenisCuti::where('jenis_cuti', 'Non-Tahunan')->count();

    return view('hrd.cuti_nontahunan', compact('user', 'cutiNonTahunan', 'totalCutiNontahunan'));
    }
    public function create(){
        $user = auth()->user();
        return view('hrd.tambah_cuti_nontahunan', compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti.*' => 'required|string|max:255', // ini akan jadi nama_cuti
            'kuota.*' => 'required|integer|min:0',
            'syarat.*' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $namaCutis = $request->input('jenis_cuti');
            $kuotas    = $request->input('kuota');
            $syarats   = $request->input('syarat');

            foreach ($namaCutis as $index => $nama) {
                $tipePenggunaan = $request->input("tipe_penggunaan_" . ($index + 1));

                JenisCuti::create([
                    'nama_cuti'       => $nama,
                    'kuota'      => $kuotas[$index],
                    'tipe_penggunaan' => $tipePenggunaan,
                    'jenis_cuti'      => 'Non-Tahunan',
                    'syarat' => $syarats[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('cuti_nontahunan')->with('success', 'Jenis cuti berhasil disimpan!');
        } catch (\Exception $e) {
        DB::rollBack();
        // Tampilkan pesan error dari Laravel / database
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
    }
}