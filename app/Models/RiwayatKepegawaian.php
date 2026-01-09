<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKepegawaian extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kepegawaian';

    protected $fillable = [
        'unitkerja_id',
        'golongan_id',
        'jabatan_id',
        'tgl_mulai',
        'tgl_selesai',
        'user_id',
    ];

    public function unitkerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unitkerja_id');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
