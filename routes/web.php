<?php

use App\Http\Controllers\AuthController;
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

    // Hanya pelapor
    Route::middleware('peran:pelapor')->group(function () {
        Route::get('/uji/pelapor', fn () => 'Halaman khusus Pelapor');
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