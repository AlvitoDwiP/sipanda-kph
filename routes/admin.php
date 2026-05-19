<?php

use App\Http\Controllers\Admin\CatatanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\GolonganController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\NotifAdminController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\UnitKerjaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('register', RegisterController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/pegawai/{pegawai}/detail', [PegawaiController::class, 'show'])->name('pegawai.show');
    Route::resource('pegawai', PegawaiController::class)->except(['show']);

    Route::resource('penugasan', PenugasanController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::post('/penugasan/{penugasan}/setujui', [PenugasanController::class, 'setujui'])->name('penugasan.setujui');
    Route::post('/penugasan/{penugasan}/revisi', [PenugasanController::class, 'revisi'])->name('penugasan.revisi');
    Route::post('/penugasan/{penugasan}/batalkan', [PenugasanController::class, 'batalkan'])->name('penugasan.batalkan');

    Route::get('/catatan-kegiatan', [CatatanController::class, 'index'])->name('catatan_kegiatan.index');
    Route::patch('/catatan-kegiatan/{catatan}/status', [CatatanController::class, 'updateStatus'])->name('catatan_kegiatan.status');
    Route::get('/catatan-kegiatan/{id}/download-pdf', [CatatanController::class, 'downloadPdf'])->name('catatan_kegiatan.pdf');

    Route::resource('golongan', GolonganController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jabatan', JabatanController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('unitkerja', UnitKerjaController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    Route::get('/notifikasi', [NotifAdminController::class, 'index'])->name('notifikasi.index');
});
