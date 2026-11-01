<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProsesLaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekapController;

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
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show')->whereNumber('laporan');
    Route::get('/laporan/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit')->whereNumber('laporan');
    Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/tinjau', [ProsesLaporanController::class, 'tinjau'])->name('laporan.tinjau')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/minta-lengkapi', [ProsesLaporanController::class, 'mintaLengkapi'])->name('laporan.mintaLengkapi')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/tandai-diteruskan', [ProsesLaporanController::class, 'tandaiDiteruskan'])->name('laporan.tandaiDiteruskan')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/kirim-ulang', [ProsesLaporanController::class, 'kirimUlang'])->name('laporan.kirimUlang')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/catat-penerusan', [ProsesLaporanController::class, 'catatPenerusan'])->name('laporan.catatPenerusan')->whereNumber('laporan');
    Route::post('/laporan/{laporan}/selesai', [ProsesLaporanController::class, 'selesaikan'])->name('laporan.selesai')->whereNumber('laporan');
    
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
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    });

    // Hanya koordinator
    Route::middleware('peran:koordinator')->group(function () {
        Route::get('/uji/koordinator', fn () => 'Halaman khusus Koordinator');
    });
});