<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;

class KaryawanController extends Controller
{
    public function index(){
        $user = auth()->user();
        $karyawan = User::whereIn('role_user', ['pegawai', 'atasan'])->get();
        return view('hrd.karyawan', compact('user', 'karyawan'));
    }
    public function create(){
        return view('hrd.tambah_karyawan');
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap'             => 'required|string|max:255',
            'nik'                      => 'required|string|min:8|max:16|unique:users,nik',
            'no_telp'                  => 'required|string|regex:/^([0-9]{10,15})$/',
            'alamat'                   => 'required|string|max:255',
            'jenis_kelamin'            => 'required|in:Laki-Laki,Perempuan',
            'no_npwp'                  => 'nullable|string|max:16', // boleh kosong
            'tanggal_mulai_kerja'      => 'required|date',
            'tanggal_selesai_kerja'    => 'nullable|date|after_or_equal:tanggal_mulai_kerja',
            'divisi'                   => 'required|string|max:100',
            'jabatan'                  => 'required|string|max:100',
            'status_karyawan'          => 'required|in:probation,PKWT,PKWTT',
            'file_ktp'                 => 'required|mimes:jpg,jpeg,png|max:2048',
            'file_npwp'                => 'nullable|mimes:jpg,jpeg,png|max:2048', // boleh kosong
            'file_sk_kontrak'          => 'required|mimes:pdf|max:2048',
            'role_user'                => 'required|in:pegawai,atasan',
            'status_akun'              => 'required|in:pengaktifan',
            'email'                    => 'required|email|unique:users,email',
            'password'                 => 'required|string|min:6',
        ]);

        $npwpPath = null;

        // Upload file
        // File KTP
        if ($request->hasFile('file_ktp')) {
            $file = $request->file('file_ktp');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;

            $destinationPath = public_path('file_ktp');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $ktpPath = 'file_ktp/' . $fileName; // path untuk disimpan di DB
        }
        if ($request->hasFile('file_npwp')) {
            $file = $request->file('file_npwp');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;

            $destinationPath = public_path('file_npwp');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $npwpPath = 'file_npwp/' . $fileName; // path untuk disimpan di DB
        }
        if ($request->hasFile('file_sk_kontrak')) {
            $file = $request->file('file_sk_kontrak');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $originalName . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;

            $destinationPath = public_path('file_sk_kontrak');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $skKontrakPath = 'file_sk_kontrak/' . $fileName; // path untuk disimpan di DB
        }

        // Simpan data ke database
        $karyawan = new User();
        $karyawan->nama_lengkap            = $request->nama_lengkap;
        $karyawan->nik                     = $request->nik;
        $karyawan->no_telp                 = $request->no_telp;
        $karyawan->alamat                  = $request->alamat;
        $karyawan->jenis_kelamin           = $request->jenis_kelamin;
        $karyawan->no_npwp                 = $request->no_npwp;
        $karyawan->tanggal_mulai_kerja     = $request->tanggal_mulai_kerja;
        $karyawan->tanggal_selesai_kerja   = $request->tanggal_selesai_kerja;
        $karyawan->divisi                  = $request->divisi;
        $karyawan->jabatan                 = $request->jabatan;
        $karyawan->status_karyawan         = $request->status_karyawan;
        $karyawan->file_ktp                = $ktpPath;
        $karyawan->file_npwp               = $npwpPath;
        $karyawan->file_sk_kontrak         = $skKontrakPath;
        $karyawan->role_user               = $request->role_user;
        $karyawan->status_akun             = $request->status_akun;
        $karyawan->email                   = $request->email;
        $karyawan->password                = Hash::make($request->password);
        $karyawan->save();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil disimpan.');
    }

    public function edit($id_user){
        $karyawan = User::findOrFail($id_user);
        return view('hrd.edit_karyawan', compact('karyawan'));
    }

    public function update(Request $request, $id_user)
{
    $karyawan = User::findOrFail($id_user);

    // ✅ Validasi input
    $validated = $request->validate([
        'nama_lengkap'          => 'required|string|max:255',
        'nik'                   => 'required|digits:16',
        'no_telp'               => 'required|digits_between:10,15',
        'alamat'                => 'required|string|min:5',
        'jenis_kelamin'         => 'required|in:Laki-Laki,Perempuan',
        'no_npwp'               => 'nullable|digits:15',
        'status_karyawan'       => 'required|in:PKWT,PKWTT,Probation',
        'tanggal_mulai_kerja'   => 'required|date',
        'tanggal_selesai_kerja' => 'nullable|date|after_or_equal:tanggal_mulai_kerja',
        'divisi'                => 'required|string|max:100',
        'jabatan'               => 'required|string|max:100',
        'role_user'             => 'required|in:atasan,pegawai',
        'status_akun'           => 'required|in:pengaktifan,penonaktifan',
        'email'                 => 'required|email',
        'file_ktp'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'file_npwp'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'file_sk_kontrak'       => 'nullable|mimes:pdf|max:2048',
    ]);

    // ✅ Update data utama
    $karyawan->nama_lengkap        = $validated['nama_lengkap'];
    $karyawan->nik                 = $validated['nik'];
    $karyawan->no_telp             = $validated['no_telp'];
    $karyawan->alamat              = $validated['alamat'];
    $karyawan->jenis_kelamin       = $validated['jenis_kelamin'];
    $karyawan->no_npwp             = $validated['no_npwp'] ?? null;
    $karyawan->tanggal_mulai_kerja = $validated['tanggal_mulai_kerja'];

    // ⬇️ Atur tanggal selesai kerja sesuai status karyawan
    if ($validated['status_karyawan'] === 'PKWT') {
        $karyawan->tanggal_selesai_kerja = $validated['tanggal_selesai_kerja'] ?? null;
    } else {
        $karyawan->tanggal_selesai_kerja = null;
    }

    $karyawan->divisi       = $validated['divisi'];
    $karyawan->jabatan      = $validated['jabatan'];
    $karyawan->role_user    = $validated['role_user'];
    $karyawan->status_akun  = $validated['status_akun'];
    $karyawan->status_karyawan = $validated['status_karyawan'];
    $karyawan->email        = $validated['email'];

    // ✅ Upload file KTP
    if ($request->hasFile('file_ktp')) {
        if ($karyawan->file_ktp && Storage::exists('public/' . $karyawan->file_ktp)) {
            Storage::delete('public/' . $karyawan->file_ktp);
        }
        $karyawan->file_ktp = $request->file('file_ktp')->store('karyawan/ktp', 'public');
    }

    // ✅ Upload file NPWP (opsional)
    if ($request->hasFile('file_npwp')) {
        if ($karyawan->file_npwp && Storage::exists('public/' . $karyawan->file_npwp)) {
            Storage::delete('public/' . $karyawan->file_npwp);
        }
        $karyawan->file_npwp = $request->file('file_npwp')->store('karyawan/npwp', 'public');
    }

    // ✅ Upload file SK Kontrak (opsional)
    if ($request->hasFile('file_sk_kontrak')) {
        if ($karyawan->file_sk_kontrak && Storage::exists('public/' . $karyawan->file_sk_kontrak)) {
            Storage::delete('public/' . $karyawan->file_sk_kontrak);
        }
        $karyawan->file_sk_kontrak = $request->file('file_sk_kontrak')->store('karyawan/sk_kontrak', 'public');
    }

    $karyawan->save();

    // ✅ Reset jatah cuti jika akun dinonaktifkan
    if ($request->status_akun === 'penonaktifan') {
        \DB::table('jatah_cuti')
            ->where('id_user', $karyawan->id_user)
            ->update([
                'total_cuti_tahunan'          => 0,
                'total_cuti_tahunan_terpakai' => 0,
                'total_cuti_bersama'          => 0,
                'sisa_cuti_tahunan'           => 0,
                'updated_at'                  => now(),
            ]);
    }

    return redirect()->route('karyawan.index')
        ->with('success', 'Data karyawan berhasil diperbarui!');
}

}