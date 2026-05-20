<?php

namespace App\Services;

use App\Models\ReportValidation;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportValidationService
{
    public function create(array $payload): ReportValidation
    {
        return ReportValidation::create([
            'report_code' => $this->generateReportCode(),
            'validation_token' => $this->generateValidationToken(),
            'report_type' => $payload['report_type'],
            'period_type' => $payload['period_type'] ?? null,
            'period_start' => $payload['period_start'] ?? null,
            'period_end' => $payload['period_end'] ?? null,
            'generated_by' => $payload['generated_by'] ?? null,
            'generated_at' => now(),
            'file_hash' => null,
            'status' => 'valid',
            'metadata' => $payload['metadata'] ?? null,
        ]);
    }

    public function generateValidationQrSvg(ReportValidation $report): string
    {
        return QrCode::format('svg')->size(120)->margin(1)->generate($report->validationUrl());
    }

    public function findByToken(string $token): ?ReportValidation
    {
        return ReportValidation::query()->with('generatedBy:id,name,role')->where('validation_token', $token)->first();
    }

    private function generateReportCode(): string
    {
        do {
            $code = 'LAP-SIPANDA-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
        } while (ReportValidation::query()->where('report_code', $code)->exists());

        return $code;
    }

    private function generateValidationToken(): string
    {
        do {
            $token = 'RPT-' . Str::upper(Str::random(20));
        } while (ReportValidation::query()->where('validation_token', $token)->exists());

        return $token;
    }
}
