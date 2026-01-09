<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    protected $table = 'ref_golongan';

    protected $fillable = [
        'nama_golongan',
        'aktif',
    ];
}
