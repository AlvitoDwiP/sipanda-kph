<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasan';

    protected $fillable = [
        'pegawai_id',
        'tugas_id',
        'status',
        'progres_persen',
        'catatan_kepegawaian',
        'catatan_progres',
        'catatan_revisi',
        'progres_updated_at',
        'selesai_at',
        'alasan_pembatalan',
        'laporan',
        'foto_progres',
    ];

    protected $casts = [
        'foto_progres' => 'array',
        'progres_updated_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(PenugasanStatusHistory::class, 'penugasan_id');
    }

    public function getIsTerlambatAttribute()
    {
        if (!$this->tugas || !$this->tugas->deadline) {
            return false;
        }

        return now()->toDateString() > $this->tugas->deadline->toDateString()
            && !in_array($this->status, ['selesai', 'dibatalkan']);
    }
}
