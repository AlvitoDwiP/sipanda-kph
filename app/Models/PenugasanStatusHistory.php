<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'penugasan_status_histories';

    protected $fillable = [
        'penugasan_id',
        'user_id',
        'status_sebelum',
        'status_sesudah',
        'catatan',
    ];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'penugasan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
