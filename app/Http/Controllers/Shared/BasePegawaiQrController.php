<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Mixin/trait-like base controller untuk operasi QR Code pegawai.
 *
 * generateQr(), regenerateQr(), cetakQr(), downloadQr() identik 100%
 * antara Admin\PegawaiController dan KPH\PegawaiController.
 *
 * Subclass hanya perlu mendefinisikan routePrefix() untuk link balik
 * yang benar di view cetak QR.
 */
abstract class BasePegawaiQrController extends Controller
{
    /**
     * Prefix route untuk role ini, mis. 'admin' atau 'kph'.
     */
    abstract protected function routePrefix(): string;

    public function generateQr(Pegawai $pegawai)
    {
        if (!$pegawai->isAktif()) {
            return back()->with('error', 'QR tidak bisa dibuat karena pegawai tidak aktif.');
        }

        if ($pegawai->hasQrToken()) {
            return back()->with('error', 'Pegawai sudah memiliki token QR.');
        }

        $pegawai->ensureQrToken();

        return back()->with('success', 'Token QR pegawai berhasil dibuat.');
    }

    public function regenerateQr(Pegawai $pegawai)
    {
        if (!$pegawai->isAktif()) {
            return back()->with('error', 'QR tidak bisa dibuat karena pegawai tidak aktif.');
        }

        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $pegawai->regenerateQrToken();

        return back()->with('success', 'Token QR pegawai berhasil diperbarui.');
    }

    public function cetakQr(Pegawai $pegawai)
    {
        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $pegawai->load(['user', 'unitkerja', 'jabatan']);

        return view('pages.admin.pegawai.qr_cetak', [
            'pegawai'     => $pegawai,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function downloadQr(Pegawai $pegawai)
    {
        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $svg      = QrCode::format('svg')->size(500)->margin(1)->generate($pegawai->qr_token);
        $filename = 'qr-pegawai-' . $pegawai->id . '.svg';

        return response($svg, 200, [
            'Content-Type'        => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
