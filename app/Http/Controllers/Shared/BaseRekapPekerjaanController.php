<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Services\RekapPekerjaanService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller dasar untuk Rekap Pekerjaan.
 *
 * Admin\RekapPekerjaanController dan KPH\RekapPekerjaanController
 * meng-extends class ini dan hanya perlu mendefinisikan routePrefix().
 * Kedua controller sebelumnya identik 100% kecuali 'scope' dan 'routePrefix'.
 */
abstract class BaseRekapPekerjaanController extends Controller
{
    /**
     * Prefix route untuk role ini, mis. 'admin' atau 'kph'.
     */
    abstract protected function routePrefix(): string;

    public function index(Request $request, RekapPekerjaanService $rekapService)
    {
        $validated = $request->validate([
            'periode'       => ['nullable', Rule::in(['harian', 'mingguan', 'bulanan'])],
            'tanggal'       => ['nullable', 'date'],
            'bulan'         => ['nullable', 'date_format:Y-m'],
            'pegawai_id'    => ['nullable', 'integer', 'exists:pegawai,id'],
            'unit_kerja_id' => ['nullable', 'integer', 'exists:ref_unitkerja,id'],
            'status'        => ['nullable', Rule::in(['belum_dikerjakan', 'sedang_dikerjakan', 'menunggu_verifikasi', 'revisi', 'selesai', 'dibatalkan'])],
            'prioritas'     => ['nullable', Rule::in(['rendah', 'sedang', 'tinggi'])],
        ]);

        $validated['periode'] = $validated['periode'] ?? 'harian';
        $range = $rekapService->resolveDateRange($validated);

        $filters = [
            'periode'       => $range['periode'],
            'tanggal'       => $range['tanggal'],
            'bulan'         => $range['bulan'],
            'pegawai_id'    => $validated['pegawai_id'] ?? null,
            'unit_kerja_id' => $validated['unit_kerja_id'] ?? null,
            'status'        => $validated['status'] ?? null,
            'prioritas'     => $validated['prioritas'] ?? null,
        ];

        $summaryTugas   = $rekapService->getRingkasanTugas($range['start'], $range['end'], $filters);
        $summaryCatatan = $rekapService->getRingkasanCatatan($range['start'], $range['end'], $filters);
        $rekapPegawai   = $rekapService->getRekapPerPegawai($range['start'], $range['end'], $filters);
        $rekapUnitKerja = $rekapService->getRekapPerUnitKerja($range['start'], $range['end'], $filters);
        $daftarTugas    = $rekapService->getDaftarTugas($range['start'], $range['end'], $filters);
        $daftarCatatan  = $rekapService->getDaftarCatatan($range['start'], $range['end'], $filters);
        $filterOptions  = $rekapService->getFilterOptions();

        return view('pages.rekap_pekerjaan.index', [
            'scope'         => $this->routePrefix(),
            'routePrefix'   => $this->routePrefix(),
            'pageTitle'     => 'Rekap Pekerjaan',
            'periodeLabel'  => $range['label'],
            'filters'       => $filters,
            'summaryTugas'  => $summaryTugas,
            'summaryCatatan'=> $summaryCatatan,
            'rekapPegawai'  => $rekapPegawai,
            'rekapUnitKerja'=> $rekapUnitKerja,
            'daftarTugas'   => $daftarTugas,
            'daftarCatatan' => $daftarCatatan,
            'filterOptions' => $filterOptions,
        ]);
    }
}
