<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiBersamaController;
use App\Http\Controllers\CutiNonTahunanController;
use App\Http\Controllers\CutiTahunanController;
use App\Http\Controllers\DashboardHRDController;
use App\Http\Controllers\DashboardSuperadminController;
use App\Http\Controllers\KaryawanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[AuthController::class,'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login',[AuthController::class,'login'])->middleware('guest');
Route::post('/logout',[AuthController::class,'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'hrd'])->group(function () {
    Route::get('/dashboard_hrd', [DashboardHRDController::class,'index'])->name('dashboard_hrd');
    Route::get('/cuti_tahunan',[CutiTahunanController::class,'index'])->name('cuti_tahunan');
    Route::post('/cuti_tahunan_store',[CutiTahunanController::class,'store'])->name('cuti_tahunan.store');
    Route::get('/cuti_bersama', [CutiBersamaController::class,'index'])->name('cuti_bersama');
    Route::post('/cuti_bersama_store',[CutiBersamaController::class,'store'])->name('cuti_bersama.store');
    Route::put('/cuti_bersama/{id_cuti_bersama}', [CutiBersamaController::class, 'update'])->name('cuti_bersama.update');
    Route::delete('/cuti_bersama/{id_cuti_bersama}', [CutiBersamaController::class, 'destroy'])->name('cuti_bersama.destroy');
    Route::get('/cuti_nontahunan', [CutiNonTahunanController::class,'index'])->name('cuti_nontahunan');
    Route::get('/tambah_cuti_nontahunan',[CutiNonTahunanController::class,'create'])->name('cuti_nontahunan.create');
    Route::post('/jenis-cuti/store', [CutiNonTahunanController::class, 'store'])->name('jenis_cuti.store');
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/tambah_karyawan', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan_store',[KaryawanController::class,'store'])->name('karyawan.store');
    Route::get('/karyawan/{id_user}/edit',[KaryawanController::class,'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id_user}', [KaryawanController::class,'update'])->name('karyawan.update');
});

Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/dashboard_superadmin', [DashboardSuperadminController::class,'index'])->name('dashboard_superadmin');
    Route::get('/karyawan/{id_user}', [DashboardSuperadminController::class, 'show'])->name('karyawan.show');
    Route::post('/karyawan/{id_user}/terima',[DashboardSuperadminController::class,'setujuiAktifkanAkun'])->name('karyawan.setuju');

});