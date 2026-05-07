<?php

use App\Http\Controllers\KPH\CatatanKegiatanController;
use App\Http\Controllers\KPH\DashboardController as KphDashboard;
use App\Http\Controllers\KPH\PegawaiController as KphPegawaiController;
use App\Http\Controllers\KPH\PenugasanController as KphPenugasanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kph'])->prefix('kph')->name('kph.')->group(function () {
    Route::get('/dashboard', [KphDashboard::class, 'index'])->name('dashboard');

    Route::get('/pegawai', [KphPegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [KphPegawaiController::class, 'show'])->name('pegawai.show');

    Route::get('/penugasan', [KphPenugasanController::class, 'index'])->name('penugasan.index');

    Route::get('/catatan-kegiatan', [CatatanKegiatanController::class, 'index'])->name('catatan_kegiatan.index');
});
