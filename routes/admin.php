<?php

use App\Http\Controllers\Admin\CatatanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DisplayJobdeskController;
use App\Http\Controllers\Admin\GolonganController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\LaporanPdfController;
use App\Http\Controllers\Admin\NotifAdminController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\RekapPekerjaanController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\UnitKerjaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/display-jobdesk', [DisplayJobdeskController::class, 'index'])->name('display-jobdesk.index');
    Route::get('/display-jobdesk/manage', [DisplayJobdeskController::class, 'manage'])->name('display-jobdesk.manage');
    Route::get('/display-jobdesk/tv', [DisplayJobdeskController::class, 'tv'])->name('display-jobdesk.tv');
    Route::post('/display-jobdesk/settings', [DisplayJobdeskController::class, 'updateSettings'])->name('display-jobdesk.settings.update');
    Route::post('/display-jobdesk/reset', [DisplayJobdeskController::class, 'reset'])->name('display-jobdesk.reset');
    Route::get('/display-jobdesk/state', [DisplayJobdeskController::class, 'state'])->name('display-jobdesk.state');
    Route::post('/display-jobdesk/scan', [DisplayJobdeskController::class, 'scan'])
        ->middleware('throttle:120,1')
        ->name('display-jobdesk.scan');

    Route::resource('register', RegisterController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/pegawai/{pegawai}/detail', [PegawaiController::class, 'show'])->name('pegawai.show');
    Route::post('/pegawai/{pegawai}/qr/generate', [PegawaiController::class, 'generateQr'])->name('pegawai.qr.generate');
    Route::post('/pegawai/{pegawai}/qr/regenerate', [PegawaiController::class, 'regenerateQr'])->name('pegawai.qr.regenerate');
    Route::get('/pegawai/{pegawai}/qr/cetak', [PegawaiController::class, 'cetakQr'])->name('pegawai.qr.cetak');
    Route::get('/pegawai/{pegawai}/qr/download', [PegawaiController::class, 'downloadQr'])->name('pegawai.qr.download');
    Route::resource('pegawai', PegawaiController::class)->except(['show']);

    Route::resource('penugasan', PenugasanController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::post('/penugasan/{penugasan}/setujui', [PenugasanController::class, 'setujui'])->name('penugasan.setujui');
    Route::post('/penugasan/{penugasan}/revisi', [PenugasanController::class, 'revisi'])->name('penugasan.revisi');
    Route::post('/penugasan/{penugasan}/batalkan', [PenugasanController::class, 'batalkan'])->name('penugasan.batalkan');

    Route::get('/catatan-kegiatan', [CatatanController::class, 'index'])->name('catatan_kegiatan.index');
    Route::get('/catatan-kegiatan/{catatan}', [CatatanController::class, 'show'])->name('catatan_kegiatan.show');
    Route::post('/catatan-kegiatan/{catatan}/setujui', [CatatanController::class, 'setujui'])->name('catatan_kegiatan.setujui');
    Route::post('/catatan-kegiatan/{catatan}/revisi', [CatatanController::class, 'revisi'])->name('catatan_kegiatan.revisi');
    Route::post('/catatan-kegiatan/{catatan}/tolak', [CatatanController::class, 'tolak'])->name('catatan_kegiatan.tolak');
    Route::get('/catatan-kegiatan/{id}/download-pdf', [CatatanController::class, 'downloadPdf'])->name('catatan_kegiatan.pdf');
    Route::get('/rekap-pekerjaan', [RekapPekerjaanController::class, 'index'])->name('rekap-pekerjaan.index');
    Route::get('/rekap-pekerjaan/export-pdf', [LaporanPdfController::class, 'exportRekapPekerjaan'])->name('rekap-pekerjaan.export-pdf');
    Route::get('/laporan/tugas/export-pdf', [LaporanPdfController::class, 'exportTugas'])->name('laporan.tugas.export-pdf');
    Route::get('/laporan/catatan/export-pdf', [LaporanPdfController::class, 'exportCatatan'])->name('laporan.catatan.export-pdf');

    Route::resource('golongan', GolonganController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('jabatan', JabatanController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('unitkerja', UnitKerjaController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    Route::get('/notifikasi', [NotifAdminController::class, 'index'])->name('notifikasi.index');
});
