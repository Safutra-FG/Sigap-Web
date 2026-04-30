<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\AdminUniversal\BerandaController;
use App\Http\Controllers\AdminUniversal\BidangController;
use App\Http\Controllers\AdminUniversal\PenggunaController;
use App\Http\Controllers\AdminUniversal\LaporanController;
use App\Http\Controllers\AdminBidang\BerandaController as BerandaBidangController;

Route::get('/', [AutentikasiController::class, 'tampilLogin'])->name('login');
Route::post('/login/proses', [AutentikasiController::class, 'prosesLogin'])->name('login.proses');
Route::post('/logout', [AutentikasiController::class, 'logout'])->name('logout');

//g
Route::prefix('admin-universal')->name('admin_universal.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'indeks'])->name('beranda');

    Route::get('/bidang', [BidangController::class, 'indeks'])->name('bidang');
    Route::post('/bidang/simpan', [BidangController::class, 'simpan'])->name('bidang.simpan');
    Route::put('/bidang/perbarui/{id}', [BidangController::class, 'perbarui'])->name('bidang.perbarui');
    Route::delete('/bidang/hapus/{id}', [BidangController::class, 'hapus'])->name('bidang.hapus');

    // CRUD PENGGUNA (Langkah 5)
    Route::get('/pengguna', [PenggunaController::class, 'indeks'])->name('pengguna');
    Route::post('/pengguna/simpan', [PenggunaController::class, 'simpan'])->name('pengguna.simpan');
    Route::delete('/pengguna/hapus/{id}', [PenggunaController::class, 'hapus'])->name('pengguna.hapus');

    Route::get('/laporan', function() { return "Halaman Kelola Laporan"; })->name('laporan');

    // KELOLA LAPORAN (Langkah 6)
    Route::get('/laporan', [LaporanController::class, 'indeks'])->name('laporan');
    Route::get('/laporan/detail/{id}', [LaporanController::class, 'detail'])->name('laporan.detail');
    Route::post('/laporan/disposisi/{id}', [LaporanController::class, 'disposisi'])->name('laporan.disposisi');
    Route::post('/laporan/tolak/{id}', [LaporanController::class, 'tolak'])->name('laporan.tolak');
});

// Grup Rute Khusus Admin Bidang (Kepala Unit/Departemen)
Route::prefix('admin-bidang')->name('admin_bidang.')->group(function () {

    // Dasbor Admin Bidang
    Route::get('/beranda', [BerandaBidangController::class, 'indeks'])->name('beranda');

});
