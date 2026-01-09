<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'ref_unitkerja';

    protected $fillable = [
        'nama_unitkerja',
        'aktif',
    ];
}
