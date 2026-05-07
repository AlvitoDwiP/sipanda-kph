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

    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/register/{user}/edit', [RegisterController::class, 'edit'])->name('register.edit');
    Route::put('/register/{user}', [RegisterController::class, 'update'])->name('register.update');
    Route::delete('/register/{user}', [RegisterController::class, 'delete'])->name('register.delete');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'delete'])->name('pegawai.delete');
    Route::get('/pegawai/{pegawai}/detail', [PegawaiController::class, 'show'])->name('pegawai.show');

    Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
    Route::get('/penugasan/create', [PenugasanController::class, 'create'])->name('penugasan.create');
    Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
    Route::get('/penugasan/{penugasan}/edit', [PenugasanController::class, 'edit'])->name('penugasan.edit');
    Route::put('/penugasan/{penugasan}', [PenugasanController::class, 'update'])->name('penugasan.update');
    Route::delete('/penugasan/{penugasan}', [PenugasanController::class, 'delete'])->name('penugasan.delete');

    Route::get('/catatan-kegiatan', [CatatanController::class, 'index'])->name('catatan_kegiatan.index');
    Route::patch('/catatan-kegiatan/{catatan}/status', [CatatanController::class, 'updateStatus'])->name('catatan_kegiatan.status');
    Route::get('/catatan-kegiatan/{id}/download-pdf', [CatatanController::class, 'downloadPdf'])->name('catatan_kegiatan.pdf');

    Route::resource('golongan', GolonganController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jabatan', JabatanController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('unitkerja', UnitKerjaController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    Route::get('/notifikasi', [NotifAdminController::class, 'index'])->name('notifikasi.index');
});
