<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\Penugasan;
use Illuminate\Support\Str;

class DisplayJobdeskService
{
    private const TOKEN_REGEX = '/^PGW-[A-Z2-9]{16}$/';

    public function scanToken(string $token): array
    {
        $token = $this->normalizeToken($token);

        if (!$this->isValidTokenFormat($token)) {
            return [
                'status' => 'invalid_format',
                'message' => 'QR tidak terbaca dengan benar. Silakan scan ulang.',
                '_pegawai_id' => null,
                '_task_count' => 0,
            ];
        }

        $pegawai = Pegawai::with(['user', 'jabatan', 'unitkerja', 'dataDiri'])
            ->where('qr_token', $token)
            ->first();

        if (!$pegawai) {
            return [
                'status' => 'invalid_token',
                'message' => 'QR tidak valid.',
                '_pegawai_id' => null,
                '_task_count' => 0,
            ];
        }

        if (!$pegawai->isAktif()) {
            return [
                'status' => 'inactive_employee',
                'message' => 'Pegawai tidak aktif. Silakan hubungi admin/KPH.',
                '_pegawai_id' => $pegawai->id,
                '_task_count' => 0,
            ];
        }

        $today = now()->toDateString();

        $rows = Penugasan::with('tugas')
            ->where('pegawai_id', $pegawai->id)
            ->whereHas('tugas', function ($q) use ($today) {
                $q->whereDate('tanggal_tugas', $today);
            })
            ->get()
            ->sort(function ($a, $b) {
                $prioWeight = fn($p) => match ($p) {
                    'tinggi' => 1,
                    'sedang' => 2,
                    default => 3,
                };

                $statusWeight = fn($s) => match ($s) {
                    'menunggu_verifikasi' => 1,
                    'revisi' => 2,
                    'sedang_dikerjakan' => 3,
                    'belum_dikerjakan', 'baru' => 4,
                    'selesai' => 5,
                    default => 6,
                };

                $cmpPrio = $prioWeight($a->tugas->prioritas ?? 'rendah') <=> $prioWeight($b->tugas->prioritas ?? 'rendah');
                if ($cmpPrio !== 0) {
                    return $cmpPrio;
                }

                $aDeadline = optional($a->tugas->deadline)->timestamp ?? PHP_INT_MAX;
                $bDeadline = optional($b->tugas->deadline)->timestamp ?? PHP_INT_MAX;
                if ($aDeadline !== $bDeadline) {
                    return $aDeadline <=> $bDeadline;
                }

                return $statusWeight($a->status) <=> $statusWeight($b->status);
            })
            ->values();

        $tasks = $rows->map(function ($item) {
            return [
                'judul' => $item->tugas->judul ?? '-',
                'instruksi' => Str::limit((string) ($item->tugas->deskripsi ?? '-'), 160),
                'prioritas' => $item->tugas->prioritas ?? '-',
                'deadline' => optional($item->tugas->deadline)->format('d-m-Y') ?? '-',
                'status' => $item->status,
                'status_label' => $this->statusLabel($item->status),
                'is_terlambat' => (bool) $item->is_terlambat,
            ];
        })->all();

        $summary = [
            'total_tugas' => $rows->count(),
            'selesai' => $rows->where('status', 'selesai')->count(),
            'belum_selesai' => $rows->filter(fn($r) => !in_array($r->status, ['selesai', 'dibatalkan']))->count(),
            'terlambat' => $rows->filter(fn($r) => $r->is_terlambat)->count(),
        ];

        $pegawaiPayload = [
            'nama' => $pegawai->user->name ?? '-',
            'jabatan' => $pegawai->jabatan->nama_jabatan ?? '-',
            'unit_kerja' => $pegawai->unitkerja->nama_unitkerja ?? '-',
            'foto_url' => $pegawai->dataDiri?->foto ? asset('storage/' . $pegawai->dataDiri->foto) : null,
        ];

        if (empty($tasks)) {
            return [
                'status' => 'empty_task',
                'message' => 'Tidak ada job desk hari ini.',
                'pegawai' => $pegawaiPayload,
                'summary' => $summary,
                'tugas' => [],
                '_pegawai_id' => $pegawai->id,
                '_task_count' => 0,
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Data job desk berhasil ditemukan.',
            'pegawai' => $pegawaiPayload,
            'summary' => $summary,
            'tugas' => $tasks,
            '_pegawai_id' => $pegawai->id,
            '_task_count' => $rows->count(),
        ];
    }

    public function normalizeToken(string $token): string
    {
        return strtoupper(trim($token));
    }

    public function isValidTokenFormat(string $token): bool
    {
        if ($token === '' || strlen($token) < 20 || strlen($token) > 20) {
            return false;
        }

        return (bool) preg_match(self::TOKEN_REGEX, $token);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'belum_dikerjakan', 'baru' => 'Belum Dikerjakan',
            'sedang_dikerjakan', 'proses' => 'Sedang Dikerjakan',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'revisi' => 'Revisi',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
