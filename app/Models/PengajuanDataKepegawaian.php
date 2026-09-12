<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDataKepegawaian extends Model
{
    //
    protected $table = 'pengajuan_data_kepegawaians';
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

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
}
