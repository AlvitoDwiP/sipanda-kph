<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayScanLog extends Model
{
    protected $table = 'display_scan_logs';

    protected $fillable = [
        'scanned_at',
        'qr_token_hash',
        'pegawai_id',
        'scanned_by',
        'status',
        'task_count',
        'message',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
