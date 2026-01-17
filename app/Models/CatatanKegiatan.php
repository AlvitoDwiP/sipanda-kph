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
        'periode_bulan',
        'periode_tahun',
        'judul',
        'deskripsi',
        'status',
        'catatan_status',
        'foto_kegiatan',
    ];

    protected $casts = [
        'foto_kegiatan' => 'array',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
