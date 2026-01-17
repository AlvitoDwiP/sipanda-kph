<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDiri extends Model
{
    use HasFactory;

    protected $table = 'data_diri';

    protected $fillable = [
        'no_hp',
        'alamat',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'foto',
        'kartu_identitas',
    ];

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'data_diri_id');
    }
}
