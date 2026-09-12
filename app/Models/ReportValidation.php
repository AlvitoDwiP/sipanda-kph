<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValidation extends Model
{
    protected $fillable = [
        'report_code',
        'validation_token',
        'report_type',
        'period_type',
        'period_start',
        'period_end',
        'generated_by',
        'generated_at',
        'file_hash',
        'status',
        'revoked_at',
        'metadata',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function isValid(): bool
    {
        return $this->status === 'valid' && $this->revoked_at === null;
    }

    public function isRevoked(): bool
    {
        return $this->status === 'revoked' || $this->revoked_at !== null;
    }

    public function statusLabel(): string
    {
        return $this->isValid() ? 'Valid' : ($this->isRevoked() ? 'Dicabut' : 'Tidak Valid');
    }

    public function periodLabel(): string
    {
        if (! $this->period_start || ! $this->period_end) {
            return '-';
        }

        return $this->period_start->format('d-m-Y').' s/d '.$this->period_end->format('d-m-Y');
    }

    public function validationUrl(): string
    {
        return route('laporan.validasi.show', ['token' => $this->validation_token]);
    }
}
