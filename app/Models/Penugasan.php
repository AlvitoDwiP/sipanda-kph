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
        'catatan_kepegawaian',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
