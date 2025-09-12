<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
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
    $request->validate([
        'nama_lengkap'             => 'required|string|max:255',
        'nik'                      => 'required|string|min:8|max:16|unique:users,nik',
        'no_telp'                  => 'required|string|regex:/^([0-9]{10,15})$/',
        'alamat'                   => 'required|string|max:255',
        'jenis_kelamin'            => 'required|in:Laki-Laki,Perempuan',
        'no_npwp'                  => 'nullable|string|max:16|unique:users,no_npwp', // boleh kosong
        'tanggal_mulai_kerja'      => 'required|date',
        'tanggal_selesai_kerja'    => 'nullable|date|after_or_equal:tanggal_mulai_kerja',
        'divisi'                   => 'required|string|max:100',
        'jabatan'                  => 'required|string|max:100',
        'status_karyawan'          => 'required|in:probation,PKWT,PKWTT',
        'file_ktp'                 => 'required|mimes:jpg,jpeg,png|max:5120', // WAJIB
        'file_npwp'                => 'nullable|mimes:jpg,jpeg,png|max:5120', // boleh kosong
        'file_sk_kontrak'          => 'required|mimes:pdf|max:5120', // WAJIB
        'role_user'                => 'required|in:pegawai,atasan',
        'email'                    => 'required|email|unique:users,email',
        'password'                 => 'required|string|min:6',
    ], [
        'nik.unique'   => 'NIK sudah terdaftar, silakan gunakan yang lain.',
        'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
        'no_npwp.unique' => 'NPWP sudah terdaftar, gunakan NPWP lain.',
        'file_ktp.required' => 'File KTP wajib diunggah.',
        'file_sk_kontrak.required' => 'File SK Kontrak wajib diunggah.',
        'file_ktp.max' => 'File KTP maksimal 5 MB.',
        'file_npwp.max'=> 'File NPWP maksimal 5 MB.',
        'file_sk_kontrak.max' => 'File SK Kontrak maksimal 5 MB.',
    ]);

    try {
        $ktpPath = $this->uploadFile($request->file('file_ktp'), 'file_ktp');
        $npwpPath = $request->hasFile('file_npwp') ? $this->uploadFile($request->file('file_npwp'), 'file_npwp') : null;
        $skKontrakPath = $this->uploadFile($request->file('file_sk_kontrak'), 'file_sk_kontrak');

        // Simpan data ke DB
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
        $karyawan->status_akun             = 'pengaktifan'; // otomatis hidden field
        $karyawan->email                   = $request->email;
        $karyawan->password                = Hash::make($request->password);
        $karyawan->save();

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil disimpan.');
    } catch (\Illuminate\Database\QueryException $e) {
        $message = 'Gagal menyimpan data. ';

        if ($e->errorInfo[1] == 1062) {
            if (str_contains($e->getMessage(), 'users_nik_unique')) {
                $message .= 'NIK sudah terdaftar.';
            } elseif (str_contains($e->getMessage(), 'users_email_unique')) {
                $message .= 'Email sudah terdaftar.';
            } elseif (str_contains($e->getMessage(), 'users_no_npwp_unique')) {
                $message .= 'NPWP sudah terdaftar.';
            } else {
                $message .= 'Data duplikat ditemukan.';
            }
        } else {
            $message .= 'Terjadi kesalahan sistem.';
        }

        return redirect()->back()
            ->withInput()
            ->withErrors(['custom_error' => $message]);
    }
}

/**
 * Fungsi bantu upload file
 */
private function uploadFile($file, $folder)
{
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $fileName = $originalName . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;

    $destinationPath = public_path($folder);

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    $file->move($destinationPath, $fileName);

    return $folder . '/' . $fileName;
}


    public function edit($id_user){
        $karyawan = User::findOrFail($id_user);
        return view('hrd.edit_karyawan', compact('karyawan'));
    }

    public function update(Request $request, $id_user)
{
    $karyawan = User::findOrFail($id_user);

    try {
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
            'status_akun'           => 'required|in:pengaktifan,nonaktif',
            'email'                 => 'required|email',
            'file_ktp'              => $karyawan->file_ktp ? 'nullable|image|mimes:jpg,jpeg,png|max:5120' : 'required|image|mimes:jpg,jpeg,png|max:5120',
            'file_npwp'             => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'file_sk_kontrak'       => $karyawan->file_sk_kontrak ? 'nullable|mimes:pdf|max:5120' : 'required|mimes:pdf|max:5120',
        ]);

        // ✅ Update data utama
        $karyawan->nama_lengkap        = $validated['nama_lengkap'];
        $karyawan->nik                 = $validated['nik'];
        $karyawan->no_telp             = $validated['no_telp'];
        $karyawan->alamat              = $validated['alamat'];
        $karyawan->jenis_kelamin       = $validated['jenis_kelamin'];
        $karyawan->no_npwp             = $validated['no_npwp'] ?? null;
        $karyawan->tanggal_mulai_kerja = $validated['tanggal_mulai_kerja'];

        // Atur tanggal selesai kerja
        if ($validated['status_karyawan'] === 'PKWT') {
            $karyawan->tanggal_selesai_kerja = $validated['tanggal_selesai_kerja'] ?? null;
        } else {
            $karyawan->tanggal_selesai_kerja = null;
        }

        $karyawan->divisi          = $validated['divisi'];
        $karyawan->jabatan         = $validated['jabatan'];
        $karyawan->role_user       = $validated['role_user'];
        $karyawan->status_akun     = $validated['status_akun'];
        $karyawan->status_karyawan = $validated['status_karyawan'];
        $karyawan->email           = $validated['email'];

        // ✅ Upload file KTP
        if ($request->hasFile('file_ktp')) {
            if ($karyawan->file_ktp && file_exists(public_path($karyawan->file_ktp))) {
                unlink(public_path($karyawan->file_ktp));
            }
            $karyawan->file_ktp = $this->uploadFileUpdate($request->file('file_ktp'), 'file_ktp');
        }

        // ✅ Upload file NPWP (opsional)
        if ($request->hasFile('file_npwp')) {
            if ($karyawan->file_npwp && file_exists(public_path($karyawan->file_npwp))) {
                unlink(public_path($karyawan->file_npwp));
            }
            $karyawan->file_npwp = $this->uploadFileUpdate($request->file('file_npwp'), 'file_npwp');
        }

        // ✅ Upload file SK Kontrak
        if ($request->hasFile('file_sk_kontrak')) {
            if ($karyawan->file_sk_kontrak && file_exists(public_path($karyawan->file_sk_kontrak))) {
                unlink(public_path($karyawan->file_sk_kontrak));
            }
            $karyawan->file_sk_kontrak = $this->uploadFileUpdate($request->file('file_sk_kontrak'), 'file_sk_kontrak');
        }

        $karyawan->save();

        // ✅ Reset jatah cuti kalau akun dinonaktifkan
        if ($request->status_akun === 'nonaktif') {
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
    } catch (QueryException $e) {
        $message = 'Gagal memperbarui data karyawan. ';
        if ($e->errorInfo[1] == 1062) { // Duplicate entry
            if (str_contains($e->getMessage(), 'users_nik_unique')) {
                $message .= 'NIK sudah terdaftar.';
            } elseif (str_contains($e->getMessage(), 'users_email_unique')) {
                $message .= 'Email sudah terdaftar.';
            } elseif (str_contains($e->getMessage(), 'users_no_npwp_unique')) {
                $message .= 'NPWP sudah terdaftar.';
            } else {
                $message .= 'Data duplikat ditemukan.';
            }
        } else {
            $message .= 'Terjadi kesalahan sistem.';
        }

        return redirect()->back()
            ->withInput()
            ->withErrors(['custom_error' => $message]);
    }
}

/**
 * Private helper untuk upload file manual ke folder public/
 */
private function uploadFileUpdate($file, $folder)
{
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $fileName = $originalName . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $extension;

    $destinationPath = public_path($folder);

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    $file->move($destinationPath, $fileName);

    return $folder . '/' . $fileName;
}


}