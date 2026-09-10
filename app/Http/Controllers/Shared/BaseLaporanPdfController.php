<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Services\RekapPekerjaanService;
use App\Services\ReportValidationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class BaseLaporanPdfController extends Controller
{
    public function exportRekapPekerjaan(Request $request, RekapPekerjaanService $rekapService, ReportValidationService $validationService)
    {
        [$filters, $range] = $this->validatedFilters($request, $rekapService);

        $summaryTugas = $rekapService->getRingkasanTugas($range['start'], $range['end'], $filters);
        $summaryCatatan = $rekapService->getRingkasanCatatan($range['start'], $range['end'], $filters);
        $rekapPegawai = $rekapService->getRekapPerPegawai($range['start'], $range['end'], $filters);
        $rekapUnitKerja = $rekapService->getRekapPerUnitKerja($range['start'], $range['end'], $filters);
        $daftarTugas = $rekapService->getDaftarTugasForExport($range['start'], $range['end'], $filters);
        $daftarCatatan = $rekapService->getDaftarCatatanForExport($range['start'], $range['end'], $filters);

        $validation = $validationService->create([
            'report_type' => 'rekap_pekerjaan',
            'period_type' => $range['periode'],
            'period_start' => $range['start']->toDateString(),
            'period_end' => $range['end']->toDateString(),
            'generated_by' => auth()->id(),
            'metadata' => ['filters' => $filters, 'periode_label' => $range['label']],
        ]);

        $pdf = Pdf::loadView('pages.laporan.pdf.rekap-pekerjaan', [
            'title' => 'Rekap Pekerjaan',
            'periodeLabel' => $range['label'],
            'summaryTugas' => $summaryTugas,
            'summaryCatatan' => $summaryCatatan,
            'rekapPegawai' => $rekapPegawai,
            'rekapUnitKerja' => $rekapUnitKerja,
            'daftarTugas' => $daftarTugas,
            'daftarCatatan' => $daftarCatatan,
            'validation' => $validation,
            'validationQrSvg' => $validationService->generateValidationQrSvg($validation),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rekap-pekerjaan-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportTugas(Request $request, RekapPekerjaanService $rekapService, ReportValidationService $validationService)
    {
        [$filters, $range] = $this->validatedFilters($request, $rekapService);
        $summaryTugas = $rekapService->getRingkasanTugas($range['start'], $range['end'], $filters);
        $daftarTugas = $rekapService->getDaftarTugasForExport($range['start'], $range['end'], $filters);

        $validation = $validationService->create([
            'report_type' => 'laporan_tugas',
            'period_type' => $range['periode'],
            'period_start' => $range['start']->toDateString(),
            'period_end' => $range['end']->toDateString(),
            'generated_by' => auth()->id(),
            'metadata' => ['filters' => $filters, 'periode_label' => $range['label']],
        ]);

        $pdf = Pdf::loadView('pages.laporan.pdf.tugas', [
            'title' => 'Laporan Tugas',
            'periodeLabel' => $range['label'],
            'summaryTugas' => $summaryTugas,
            'daftarTugas' => $daftarTugas,
            'validation' => $validation,
            'validationQrSvg' => $validationService->generateValidationQrSvg($validation),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-tugas-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportCatatan(Request $request, RekapPekerjaanService $rekapService, ReportValidationService $validationService)
    {
        [$filters, $range] = $this->validatedFilters($request, $rekapService);
        $summaryCatatan = $rekapService->getRingkasanCatatan($range['start'], $range['end'], $filters);
        $daftarCatatan = $rekapService->getDaftarCatatanForExport($range['start'], $range['end'], $filters);

        $validation = $validationService->create([
            'report_type' => 'laporan_catatan',
            'period_type' => $range['periode'],
            'period_start' => $range['start']->toDateString(),
            'period_end' => $range['end']->toDateString(),
            'generated_by' => auth()->id(),
            'metadata' => ['filters' => $filters, 'periode_label' => $range['label']],
        ]);

        $pdf = Pdf::loadView('pages.laporan.pdf.catatan', [
            'title' => 'Laporan Catatan Kegiatan',
            'periodeLabel' => $range['label'],
            'summaryCatatan' => $summaryCatatan,
            'daftarCatatan' => $daftarCatatan,
            'validation' => $validation,
            'validationQrSvg' => $validationService->generateValidationQrSvg($validation),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-catatan-' . now()->format('Ymd-His') . '.pdf');
    }

    private function validatedFilters(Request $request, RekapPekerjaanService $rekapService): array
    {
        $validated = $request->validate([
            'periode' => ['nullable', Rule::in(['harian', 'mingguan', 'bulanan'])],
            'tanggal' => ['nullable', 'date'],
            'bulan' => ['nullable', 'date_format:Y-m'],
            'pegawai_id' => ['nullable', 'integer', 'exists:pegawai,id'],
            'unit_kerja_id' => ['nullable', 'integer', 'exists:ref_unitkerja,id'],
            'status' => ['nullable', Rule::in(['belum_dikerjakan', 'sedang_dikerjakan', 'menunggu_verifikasi', 'revisi', 'selesai', 'dibatalkan'])],
            'prioritas' => ['nullable', Rule::in(['rendah', 'sedang', 'tinggi'])],
        ]);

        $validated['periode'] = $validated['periode'] ?? 'harian';
        $range = $rekapService->resolveDateRange($validated);

        $filters = [
            'periode' => $range['periode'],
            'tanggal' => $range['tanggal'],
            'bulan' => $range['bulan'],
            'pegawai_id' => $validated['pegawai_id'] ?? null,
            'unit_kerja_id' => $validated['unit_kerja_id'] ?? null,
            'status' => $validated['status'] ?? null,
            'prioritas' => $validated['prioritas'] ?? null,
        ];

        return [$filters, $range];
    }
}
