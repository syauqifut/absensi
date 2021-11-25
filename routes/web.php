<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//halaman pertama ketika membuka aplikasi
Route::get('/', function () {
    return view('auth.login');
});

//halaman halaman jika sudah login
Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    //ketika baru login 
    Route::get('/dashboard',[AbsensiController::class, 'route']);

    //karyawan
    Route::get('karyawan',[AbsensiController::class, 'index'])->name('karyawan.index');
    Route::post('karyawanhadir',[AbsensiController::class, 'hadir'])->name('karyawan.hadir');
    Route::post('karyawanizin',[AbsensiController::class, 'izin'])->name('karyawan.izin');
    Route::post('karyawanpulang',[AbsensiController::class, 'pulang'])->name('karyawan.pulang');
    Route::get('karyawan/riwayat',[AbsensiController::class, 'riwayat'])->name('karyawan.riwayat');

    //admin
    Route::get('admin',[AbsensiController::class, 'indexadmin'])->name('admin.index');
    Route::get('admin/pengajuan',[AbsensiController::class, 'pengajuan'])->name('admin.pengajuan');
    Route::post('admin/izinkan',[AbsensiController::class, 'izinkan'])->name('admin.izinkan');
    Route::post('admin/tolak',[AbsensiController::class, 'tolak'])->name('admin.tolak');
});
