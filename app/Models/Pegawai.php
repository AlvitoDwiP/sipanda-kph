<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'unitkerja_id',
        'golongan_id',
        'jabatan_id',
        'status_pegawai',
        'qr_token',
        'qr_generated_at',
        'qr_regenerated_at',
        'data_diri_id',
    ];

    protected $casts = [
        'qr_generated_at' => 'datetime',
        'qr_regenerated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function dataDiri()
    {
        return $this->belongsTo(DataDiri::class, 'data_diri_id');
    }

    public function penugasan()
    {
        return $this->hasMany(Penugasan::class, 'pegawai_id');
    }

    public function tugas()
    {
        return $this->belongsToMany(Tugas::class, 'penugasan', 'pegawai_id', 'tugas_id')
            ->withPivot(['status', 'catatan_kepegawaian', 'laporan', 'foto_progres'])
            ->withTimestamps();
    }

    public function catatanKegiatan()
    {
        return $this->hasMany(CatatanKegiatan::class, 'pegawai_id');
    }

    public function isAktif(): bool
    {
        return $this->status_pegawai === 'aktif';
    }

    public function hasQrToken(): bool
    {
        return ! empty($this->qr_token);
    }

    public function hasValidQrToken(): bool
    {
        return $this->hasQrToken() && $this->isAktif();
    }

    public static function generateUniqueQrToken(): string
    {
        do {
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $randomPart = '';
            for ($i = 0; $i < 16; $i++) {
                $randomPart .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $token = 'PGW-'.$randomPart;
        } while (self::where('qr_token', $token)->exists());

        return $token;
    }

    public function ensureQrToken(): void
    {
        if ($this->hasQrToken()) {
            return;
        }

        $this->update([
            'qr_token' => self::generateUniqueQrToken(),
            'qr_generated_at' => now(),
        ]);
    }

    public function regenerateQrToken(): void
    {
        $current = $this->qr_token;
        do {
            $newToken = self::generateUniqueQrToken();
        } while ($newToken === $current);

        $this->update([
            'qr_token' => $newToken,
            'qr_regenerated_at' => now(),
            'qr_generated_at' => $this->qr_generated_at ?? now(),
        ]);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->when($keyword, function ($query, $q) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('nip', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })
                ->orWhereHas('unitkerja', function ($u) use ($q) {
                    $u->where('nama_unitkerja', 'like', "%{$q}%");
                })
                ->orWhereHas('golongan', function ($g) use ($q) {
                    $g->where('nama_golongan', 'like', "%{$q}%");
                })
                ->orWhereHas('jabatan', function ($j) use ($q) {
                    $j->where('nama_jabatan', 'like', "%{$q}%");
                });
            });
        });
    }
}
