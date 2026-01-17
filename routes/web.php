<?php

use App\Http\Controllers\Admin\CatatanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\GolonganController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\UnitKerjaController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\NotifAdminController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\RiwayatKepegawaianController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboard;
use App\Http\Controllers\KPH\DashboardController as KphDashboard;
use App\Http\Controllers\Pegawai\DataDiriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KPH\PegawaiController as KphPegawaiController;
use App\Http\Controllers\Pegawai\DataKepegawaianController;
use App\Http\Controllers\Pegawai\TugasController as TugasSayaController;
use App\Http\Controllers\KPH\PenugasanController as KphPenugasanController;
use App\Http\Controllers\KPH\RiwayatKepegawaianController as KphRiwayatKepegawaianController;
use App\Http\Controllers\Pegawai\CatatanKegiatanController;
use App\Http\Controllers\Pegawai\DirektoriController;
use App\Http\Controllers\Pegawai\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/register/waiting', function () {
    return view('auth.register-waiting');
})->name('register.waiting');

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

    Route::get('/golongan', [GolonganController::class, 'index'])->name('index.golongan');
    Route::post('/golongan', [GolonganController::class, 'store'])->name('store.golongan');
    Route::put('/golongan/{id}', [GolonganController::class, 'update'])->name('update.golongan');
    Route::delete('/golongan/{id}', [GolonganController::class, 'delete'])->name('delete.golongan');

    Route::get('/jabatan', [JabatanController::class, 'index'])->name('index.jabatan');
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('store.jabatan');
    Route::put('/jabatan/{id}', [JabatanController::class, 'update'])->name('update.jabatan');
    Route::delete('/jabatan/{id}', [JabatanController::class, 'delete'])->name('delete.jabatan');

    Route::get('/unitkerja', [UnitKerjaController::class, 'index'])->name('index.unitkerja');
    Route::post('/unitkerja', [UnitKerjaController::class, 'store'])->name('store.unitkerja');
    Route::put('/unitkerja/{id}', [UnitKerjaController::class, 'update'])->name('update.unitkerja');
    Route::delete('/unitkerja/{id}', [UnitKerjaController::class, 'delete'])->name('delete.unitkerja');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    Route::get('/notifikasi', [NotifAdminController::class, 'index'])->name('notifikasi.index');
});

Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboard::class, 'index'])->name('dashboard');

    Route::get('/data-diri', [DataDiriController::class, 'index'])->name('data_diri.index');
    Route::post('/data-diri', [DataDiriController::class, 'store'])->name('data_diri.store');
    Route::put('/data-diri', [DataDiriController::class, 'update'])->name('data_diri.update');

    Route::get('/data-kepegawaian', [DataKepegawaianController::class, 'index'])->name('data_kepegawaian.index');
    Route::put('/data-kepegawaian/{pegawai}', [DataKepegawaianController::class, 'updateKepegawaian'])->name('data_kepegawaian.update');

    Route::get('/tugas-saya', [TugasSayaController::class, 'index'])->name('tugas.index');
    Route::patch('/tugas-saya/penugasan/{penugasan}/status',[TugasSayaController::class, 'updateStatus'])->name('tugas.update-status');

    Route::get('/catatan-kegiatan', [CatatanKegiatanController::class, 'index'])->name('catatan_kegiatan.index');
    Route::get('/catatan-kegiatan/create', [CatatanKegiatanController::class, 'create'])->name('catatan_kegiatan.create');
    Route::post('/catatan-kegiatan', [CatatanKegiatanController::class, 'store'])->name('catatan_kegiatan.store');
    Route::get('/catatan-kegiatan/{id}/edit', [CatatanKegiatanController::class, 'edit'])->name('catatan_kegiatan.edit');
    Route::put('/catatan-kegiatan/{id}', [CatatanKegiatanController::class, 'update'])->name('catatan_kegiatan.update');
    Route::delete('/catatan-kegiatan/{id}', [CatatanKegiatanController::class, 'delete'])->name('catatan_kegiatan.delete');
    Route::get('/catatan-kegiatan/{id}/download-pdf', [CatatanKegiatanController::class, 'downloadPdf'])->name('catatan_kegiatan.pdf');

    Route::get('/direktori', [DirektoriController::class, 'index'])->name('direktori.index');
    Route::get('/direktori/{id}', [DirektoriController::class, 'show'])->name('direktori.show');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
});

Route::middleware(['auth', 'role:kph'])->prefix('kph')->name('kph.')->group(function () {
    Route::get('/dashboard', [KphDashboard::class, 'index'])->name('dashboard');

    Route::get('/pegawai', [KphPegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [KphPegawaiController::class, 'show'])->name('pegawai.show');

    Route::get('/penugasan', [KphPenugasanController::class, 'index'])->name('penugasan.index');

    Route::get('/catatan-kegiatan', [CatatanController::class, 'index'])->name('catatan_kegiatan.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
