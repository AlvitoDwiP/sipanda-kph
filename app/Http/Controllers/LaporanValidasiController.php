<?php

namespace App\Http\Controllers;

use App\Services\ReportValidationService;

class LaporanValidasiController extends Controller
{
    public function show(string $token, ReportValidationService $validationService)
    {
        $report = $validationService->findByToken($token);

        if (! $report) {
            return response()->view('pages.laporan.validasi.show', [
                'status' => 'invalid',
                'message' => 'Dokumen tidak valid atau tidak ditemukan.',
                'report' => null,
            ], 404);
        }

        if ($report->isRevoked()) {
            return view('pages.laporan.validasi.show', [
                'status' => 'revoked',
                'message' => 'Dokumen pernah diterbitkan tetapi sudah dicabut/tidak berlaku.',
                'report' => $report,
            ]);
        }

        return view('pages.laporan.validasi.show', [
            'status' => 'valid',
            'message' => 'Dokumen valid dan terdaftar di SIPANDA-KPH.',
            'report' => $report,
        ]);
    }
}
