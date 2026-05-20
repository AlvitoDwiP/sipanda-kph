<?php

use App\Http\Controllers\KPH\CatatanKegiatanController;
use App\Http\Controllers\KPH\DashboardController as KphDashboard;
use App\Http\Controllers\KPH\DisplayJobdeskController as KphDisplayJobdeskController;
use App\Http\Controllers\KPH\LaporanPdfController as KphLaporanPdfController;
use App\Http\Controllers\KPH\PegawaiController as KphPegawaiController;
use App\Http\Controllers\KPH\PenugasanController as KphPenugasanController;
use App\Http\Controllers\KPH\RekapPekerjaanController as KphRekapPekerjaanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin,admin,kph'])->prefix('kph')->name('kph.')->group(function () {
    Route::get('/dashboard', [KphDashboard::class, 'index'])->name('dashboard');
    Route::get('/display-jobdesk', [KphDisplayJobdeskController::class, 'index'])->name('display-jobdesk.index');
    Route::get('/display-jobdesk/manage', [KphDisplayJobdeskController::class, 'manage'])->name('display-jobdesk.manage');
    Route::get('/display-jobdesk/tv', [KphDisplayJobdeskController::class, 'tv'])->name('display-jobdesk.tv');
    Route::post('/display-jobdesk/settings', [KphDisplayJobdeskController::class, 'updateSettings'])->name('display-jobdesk.settings.update');
    Route::post('/display-jobdesk/reset', [KphDisplayJobdeskController::class, 'reset'])->name('display-jobdesk.reset');
    Route::get('/display-jobdesk/state', [KphDisplayJobdeskController::class, 'state'])->name('display-jobdesk.state');
    Route::post('/display-jobdesk/scan', [KphDisplayJobdeskController::class, 'scan'])
        ->middleware('throttle:120,1')
        ->name('display-jobdesk.scan');

    Route::get('/pegawai', [KphPegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [KphPegawaiController::class, 'show'])->name('pegawai.show');
    Route::post('/pegawai/{pegawai}/qr/generate', [KphPegawaiController::class, 'generateQr'])->name('pegawai.qr.generate');
    Route::post('/pegawai/{pegawai}/qr/regenerate', [KphPegawaiController::class, 'regenerateQr'])->name('pegawai.qr.regenerate');
    Route::get('/pegawai/{pegawai}/qr/cetak', [KphPegawaiController::class, 'cetakQr'])->name('pegawai.qr.cetak');
    Route::get('/pegawai/{pegawai}/qr/download', [KphPegawaiController::class, 'downloadQr'])->name('pegawai.qr.download');

    Route::resource('penugasan', KphPenugasanController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::post('/penugasan/{penugasan}/setujui', [KphPenugasanController::class, 'setujui'])->name('penugasan.setujui');
    Route::post('/penugasan/{penugasan}/revisi', [KphPenugasanController::class, 'revisi'])->name('penugasan.revisi');
    Route::post('/penugasan/{penugasan}/batalkan', [KphPenugasanController::class, 'batalkan'])->name('penugasan.batalkan');

    Route::get('/catatan-kegiatan', [CatatanKegiatanController::class, 'index'])->name('catatan_kegiatan.index');
    Route::get('/catatan-kegiatan/{catatan}', [CatatanKegiatanController::class, 'show'])->name('catatan_kegiatan.show');
    Route::post('/catatan-kegiatan/{catatan}/setujui', [CatatanKegiatanController::class, 'setujui'])->name('catatan_kegiatan.setujui');
    Route::post('/catatan-kegiatan/{catatan}/revisi', [CatatanKegiatanController::class, 'revisi'])->name('catatan_kegiatan.revisi');
    Route::post('/catatan-kegiatan/{catatan}/tolak', [CatatanKegiatanController::class, 'tolak'])->name('catatan_kegiatan.tolak');
    Route::get('/rekap-pekerjaan', [KphRekapPekerjaanController::class, 'index'])->name('rekap-pekerjaan.index');
    Route::get('/rekap-pekerjaan/export-pdf', [KphLaporanPdfController::class, 'exportRekapPekerjaan'])->name('rekap-pekerjaan.export-pdf');
    Route::get('/laporan/tugas/export-pdf', [KphLaporanPdfController::class, 'exportTugas'])->name('laporan.tugas.export-pdf');
    Route::get('/laporan/catatan/export-pdf', [KphLaporanPdfController::class, 'exportCatatan'])->name('laporan.catatan.export-pdf');
});
