<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'ref_unitkerja';

    protected $fillable = [
        'nama_unitkerja',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'unitkerja_id');
    }
}
