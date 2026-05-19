<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'catatan_kegiatan';

    protected $fillable = [
        'pegawai_id',
        'penugasan_id',
        'periode_bulan',
        'periode_tahun',
        'tanggal_kegiatan',
        'judul',
        'deskripsi',
        'hasil_kegiatan',
        'kendala',
        'status',
        'status_verifikasi',
        'catatan_verifikasi',
        'catatan_status',
        'diverifikasi_oleh',
        'diverifikasi_at',
        'foto_kegiatan',
    ];

    protected $casts = [
        'foto_kegiatan' => 'array',
        'tanggal_kegiatan' => 'date',
        'diverifikasi_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'penugasan_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function getStatusVerifikasiLabelAttribute(): string
    {
        return match ($this->status_verifikasi) {
            'disetujui' => 'Disetujui',
            'revisi' => 'Revisi',
            'ditolak' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    public function canBeEditedByPegawai(): bool
    {
        return $this->status_verifikasi === 'revisi';
    }
}
