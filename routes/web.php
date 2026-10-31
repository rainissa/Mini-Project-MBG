<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Harus login
Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('beranda'))->name('beranda');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/laporan/{laporan}/bukti', [LaporanController::class, 'bukti'])->name('laporan.bukti');

    // Hanya pelapor
    Route::middleware('peran:pelapor')->group(function () {
        Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::get('/sekolah/{sekolah}/distribusi', [LaporanController::class, 'distribusiPerSekolah'])->name('sekolah.distribusi');
    });

    // Hanya pemantau
    Route::middleware('peran:pemantau')->group(function () {
        Route::get('/uji/pemantau', fn () => 'Halaman khusus Pemantau');
    });

    // Pemantau atau koordinator
    Route::middleware('peran:pemantau,koordinator')->group(function () {
        Route::get('/uji/rekap', fn () => 'Halaman rekap (Pemantau dan Koordinator)');
    });

    // Hanya koordinator
    Route::middleware('peran:koordinator')->group(function () {
        Route::get('/uji/koordinator', fn () => 'Halaman khusus Koordinator');
    });
});