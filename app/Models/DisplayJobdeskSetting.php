<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayJobdeskSetting extends Model
{
    protected $table = 'display_jobdesk_settings';

    protected $fillable = [
        'display_duration_seconds',
        'show_employee_photo',
        'reset_requested_at',
    ];

    protected $casts = [
        'show_employee_photo' => 'boolean',
        'reset_requested_at' => 'datetime',
    ];

    public static function active(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'display_duration_seconds' => 10,
                'show_employee_photo' => false,
            ]
        );
    }
}
