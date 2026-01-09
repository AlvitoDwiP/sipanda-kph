<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'deadline',
        'prioritas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penugasan()
    {
        return $this->hasMany(Penugasan::class, 'tugas_id');
    }
}
