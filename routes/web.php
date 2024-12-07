<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\TempatKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // presensi
    Route::get('presensi', [PresensiController::class, 'create'])->name('presensi.index');
    Route::post('presensi', [PresensiController::class, 'store'])->name('presensi.store');
    // akun
    Route::get('akun', [AkunController::class, 'index'])->name('akun.index');
    Route::post('akun/ubah-password', [AkunController::class, 'ubahPassword'])->name('akun.ubah-password');
    // riwayat
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('riwayat/data', [RiwayatController::class, 'data'])->name('riwayat.data');
    Route::post('riwayat/cetak', [RiwayatController::class, 'cetak'])->name('riwayat.cetak');
    Route::middleware('role:dev|admin')->group(function () {
        Route::post('tempat-kerja/ubah-koordinat/{tempatKerja}', [TempatKerjaController::class, 'ubahKoordinat'])->name('tempat-kerja.ubah-koordinat');

        Route::resource('tempat-kerja', TempatKerjaController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::resource('pegawai', PegawaiController::class);
        Route::resource('pengaturan', PengaturanController::class);
    });
});

