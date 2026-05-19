<?php

use App\Http\Controllers\Pegawai\CatatanKegiatanController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboard;
use App\Http\Controllers\Pegawai\DataDiriController;
use App\Http\Controllers\Pegawai\DataKepegawaianController;
use App\Http\Controllers\Pegawai\DirektoriController;
use App\Http\Controllers\Pegawai\NotifikasiController;
use App\Http\Controllers\Pegawai\TugasController as TugasSayaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboard::class, 'index'])->name('dashboard');

    Route::get('/data-diri', [DataDiriController::class, 'index'])->name('data_diri.index');
    Route::post('/data-diri', [DataDiriController::class, 'store'])->name('data_diri.store');
    Route::put('/data-diri', [DataDiriController::class, 'update'])->name('data_diri.update');

    Route::get('/data-kepegawaian', [DataKepegawaianController::class, 'index'])->name('data_kepegawaian.index');
    Route::put('/data-kepegawaian/{pegawai}', [DataKepegawaianController::class, 'updateKepegawaian'])->name('data_kepegawaian.update');

    Route::get('/tugas-saya', [TugasSayaController::class, 'index'])->name('tugas.index');
    Route::get('/tugas-saya/{tugas}', [TugasSayaController::class, 'show'])->name('tugas.show');
    Route::post('/tugas-saya/penugasan/{penugasan}/mulai', [TugasSayaController::class, 'mulai'])->name('tugas.mulai');
    Route::post('/tugas-saya/penugasan/{penugasan}/progres', [TugasSayaController::class, 'updateProgres'])->name('tugas.progres');
    Route::post('/tugas-saya/penugasan/{penugasan}/kirim-verifikasi', [TugasSayaController::class, 'kirimVerifikasi'])->name('tugas.kirim-verifikasi');
    Route::patch('/tugas-saya/penugasan/{penugasan}/status', [TugasSayaController::class, 'updateStatus'])->name('tugas.update-status');

    Route::resource('catatan-kegiatan', CatatanKegiatanController::class)
        ->only(['index', 'edit', 'update', 'destroy', 'show'])
        ->names('catatan_kegiatan');
    Route::get('/tugas-saya/penugasan/{penugasan}/catatan/create', [CatatanKegiatanController::class, 'createFromTugas'])->name('tugas.catatan.create');
    Route::post('/tugas-saya/penugasan/{penugasan}/catatan', [CatatanKegiatanController::class, 'storeFromTugas'])->name('tugas.catatan.store');
    Route::get('/catatan-kegiatan/{id}/download-pdf', [CatatanKegiatanController::class, 'downloadPdf'])->name('catatan_kegiatan.pdf');

    Route::get('/direktori', [DirektoriController::class, 'index'])->name('direktori.index');
    Route::get('/direktori/{id}', [DirektoriController::class, 'show'])->name('direktori.show');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
});
