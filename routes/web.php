<?php

use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TempatKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
Route::middleware('auth', 'role:admin')->group(function () {
    Route::post('/tempat-kerja/ubah-koordinat', [TempatKerjaController::class, 'ubahKoordinat'])->name('tempat-kerja.ubah-koordinat');

    Route::resource('tempat-kerja', TempatKerjaController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('pegawai', PegawaiController::class);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/presensi', [App\Http\Controllers\HomeController::class, 'index'])->name('presensi');
Route::get('/riwayat', [App\Http\Controllers\HomeController::class, 'index'])->name('riwayat');
Route::get('/akun', [App\Http\Controllers\HomeController::class, 'index'])->name('akun');
Route::get('/pengaturan', [App\Http\Controllers\HomeController::class, 'index'])->name('pengaturan');

