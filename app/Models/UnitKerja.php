<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitKerja extends Model
{
    use HasFactory;
    protected $table = 'ref_unitkerja';

    protected $fillable = [
        'nama_unitkerja',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'unitkerja_id');
    }
}
