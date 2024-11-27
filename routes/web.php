<?php

use App\Http\Controllers\JabatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
Route::middleware('auth', 'role:admin')->group(function () {
    Route::resource('jabatan', JabatanController::class);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/presensi', [App\Http\Controllers\HomeController::class, 'index'])->name('presensi');
Route::get('/riwayat', [App\Http\Controllers\HomeController::class, 'index'])->name('riwayat');
Route::get('/akun', [App\Http\Controllers\HomeController::class, 'index'])->name('akun');
Route::get('/lokasi', [App\Http\Controllers\HomeController::class, 'index'])->name('lokasi');
Route::get('/pegawai', [App\Http\Controllers\HomeController::class, 'index'])->name('pegawai');
Route::get('/pengaturan', [App\Http\Controllers\HomeController::class, 'index'])->name('pengaturan');

