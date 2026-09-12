<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Shared\BaseCatatanKegiatanController;
use App\Models\CatatanKegiatan;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller catatan kegiatan untuk role Admin.
 * Extends BaseCatatanKegiatanController dan menambahkan downloadPdf()
 * yang merupakan fitur eksklusif Admin.
 */
class CatatanController extends BaseCatatanKegiatanController
{
    protected function routePrefix(): string
    {
        return 'admin';
    }

    public function downloadPdf($id)
    {
        $catatan = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
            'pegawai.golongan',
        ])->findOrFail($id);

        if ($catatan->status_verifikasi !== 'disetujui') {
            abort(403, 'Catatan belum disetujui');
        }

        $pdf = Pdf::loadView('pdf.admin.catatan_kegiatan', compact('catatan'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Catatan-Kegiatan-'.$catatan->pegawai->user->name.'.pdf');
    }
}
