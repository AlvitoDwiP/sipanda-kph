<?php

namespace App\Services;

use App\Models\CatatanKegiatan;
use App\Models\Penugasan;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DashboardMonitoringService
{
    private ?string $tugasDateColumn = null;

    public function getDashboardData(Carbon $tanggal, ?int $unitKerjaId = null): array
    {
        $tugasDateColumn = $this->getTugasDateColumn();

        $penugasanQuery = Penugasan::query()
            ->with(['tugas', 'pegawai.user', 'pegawai.unitkerja'])
            ->whereHas('tugas', function (Builder $q) use ($tanggal, $tugasDateColumn) {
                $q->whereDate($tugasDateColumn, $tanggal->toDateString());
            });

        if ($unitKerjaId) {
            $penugasanQuery->whereHas('pegawai', function (Builder $q) use ($unitKerjaId) {
                $q->where('unitkerja_id', $unitKerjaId);
            });
        }

        $summaryTugas = [
            'total_tugas' => (clone $penugasanQuery)->count(),
            'tugas_belum_dikerjakan' => (clone $penugasanQuery)->where('status', 'belum_dikerjakan')->count(),
            'tugas_sedang_dikerjakan' => (clone $penugasanQuery)->where('status', 'sedang_dikerjakan')->count(),
            'tugas_menunggu_verifikasi' => (clone $penugasanQuery)->where('status', 'menunggu_verifikasi')->count(),
            'tugas_revisi' => (clone $penugasanQuery)->where('status', 'revisi')->count(),
            'tugas_selesai' => (clone $penugasanQuery)->where('status', 'selesai')->count(),
            'tugas_dibatalkan' => (clone $penugasanQuery)->where('status', 'dibatalkan')->count(),
            'tugas_terlambat' => (clone $penugasanQuery)
                ->whereHas('tugas', function (Builder $q) {
                    $q->whereDate('deadline', '<', now()->toDateString());
                })
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count(),
        ];

        $catatanQuery = CatatanKegiatan::query()
            ->with(['pegawai.user', 'pegawai.unitkerja', 'penugasan.tugas'])
            ->where(function (Builder $q) use ($tanggal) {
                $q->whereDate('tanggal_kegiatan', $tanggal->toDateString())
                    ->orWhereDate('created_at', $tanggal->toDateString());
            });

        if ($unitKerjaId) {
            $catatanQuery->whereHas('pegawai', function (Builder $q) use ($unitKerjaId) {
                $q->where('unitkerja_id', $unitKerjaId);
            });
        }

        $summaryCatatan = [
            'catatan_menunggu_verifikasi' => (clone $catatanQuery)->where('status_verifikasi', 'menunggu_verifikasi')->count(),
            'catatan_revisi' => (clone $catatanQuery)->where('status_verifikasi', 'revisi')->count(),
            'catatan_ditolak' => (clone $catatanQuery)->where('status_verifikasi', 'ditolak')->count(),
            'catatan_disetujui_hari_ini' => (clone $catatanQuery)
                ->where('status_verifikasi', 'disetujui')
                ->whereDate('diverifikasi_at', $tanggal->toDateString())
                ->count(),
        ];

        $tugasMendesak = (clone $penugasanQuery)
            ->whereHas('tugas', fn(Builder $q) => $q->where('prioritas', 'tinggi'))
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->orderByRaw("CASE status WHEN 'menunggu_verifikasi' THEN 1 WHEN 'revisi' THEN 2 WHEN 'sedang_dikerjakan' THEN 3 ELSE 4 END")
            ->get()
            ->sortBy(fn($p) => optional($p->tugas->deadline)->timestamp ?? PHP_INT_MAX)
            ->take(8)
            ->values();

        $tugasTerlambat = (clone $penugasanQuery)
            ->whereHas('tugas', function (Builder $q) {
                $q->whereDate('deadline', '<', now()->toDateString());
            })
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('status')
            ->get()
            ->sortBy(fn($p) => optional($p->tugas->deadline)->timestamp ?? PHP_INT_MAX)
            ->take(8)
            ->values();

        $catatanMenungguVerifikasi = (clone $catatanQuery)
            ->where('status_verifikasi', 'menunggu_verifikasi')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $pegawaiBelumUpdate = (clone $penugasanQuery)
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->where(function (Builder $q) {
                $q->where('status', 'belum_dikerjakan')
                    ->orWhere('progres_persen', 0)
                    ->orWhereNull('progres_updated_at');
            })
            ->get()
            ->groupBy('pegawai_id')
            ->map(function (Collection $items) {
                $first = $items->first();
                return [
                    'pegawai' => $first->pegawai,
                    'jumlah_tugas_hari_ini' => $items->count(),
                    'jumlah_belum_update' => $items->filter(function ($row) {
                        return $row->status === 'belum_dikerjakan' || (int) $row->progres_persen === 0 || !$row->progres_updated_at;
                    })->count(),
                ];
            })
            ->take(8)
            ->values();

        $summaryUnitKerja = $this->getSummaryUnitKerja($tanggal, $unitKerjaId, $tugasDateColumn);

        return compact(
            'summaryTugas',
            'summaryCatatan',
            'tugasMendesak',
            'tugasTerlambat',
            'catatanMenungguVerifikasi',
            'pegawaiBelumUpdate',
            'summaryUnitKerja'
        );
    }

    private function getSummaryUnitKerja(Carbon $tanggal, ?int $unitKerjaId = null, string $tugasDateColumn = 'deadline'): Collection
    {
        $query = UnitKerja::query()
            ->selectRaw('ref_unitkerja.id, ref_unitkerja.nama_unitkerja, COUNT(penugasan.id) as total_tugas')
            ->leftJoin('pegawai', 'pegawai.unitkerja_id', '=', 'ref_unitkerja.id')
            ->leftJoin('penugasan', 'penugasan.pegawai_id', '=', 'pegawai.id')
            ->leftJoin('tugas', function ($join) use ($tanggal) {
                $join->on('tugas.id', '=', 'penugasan.tugas_id')
                    ->whereDate('tugas.' . $this->getTugasDateColumn(), '=', $tanggal->toDateString());
            })
            ->whereNotNull('tugas.id')
            ->groupBy('ref_unitkerja.id', 'ref_unitkerja.nama_unitkerja');

        if ($unitKerjaId) {
            $query->where('ref_unitkerja.id', $unitKerjaId);
        }

        return $query->get()->map(function ($unit) use ($tanggal) {
            $base = Penugasan::query()
                ->whereHas('tugas', fn(Builder $q) => $q->whereDate($this->getTugasDateColumn(), $tanggal->toDateString()))
                ->whereHas('pegawai', fn(Builder $q) => $q->where('unitkerja_id', $unit->id));

            $selesai = (clone $base)->where('status', 'selesai')->count();
            $belumSelesai = (clone $base)->whereNotIn('status', ['selesai', 'dibatalkan'])->count();
            $terlambat = (clone $base)
                ->whereHas('tugas', fn(Builder $q) => $q->whereDate('deadline', '<', now()->toDateString()))
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->count();

            $persentaseSelesai = $unit->total_tugas > 0
                ? (int) round(($selesai / $unit->total_tugas) * 100)
                : 0;

            return [
                'nama_unitkerja' => $unit->nama_unitkerja,
                'total_tugas' => (int) $unit->total_tugas,
                'selesai' => $selesai,
                'belum_selesai' => $belumSelesai,
                'terlambat' => $terlambat,
                'persentase_selesai' => $persentaseSelesai,
            ];
        });
    }

    private function getTugasDateColumn(): string
    {
        if ($this->tugasDateColumn !== null) {
            return $this->tugasDateColumn;
        }

        $this->tugasDateColumn = Schema::hasColumn('tugas', 'tanggal_tugas')
            ? 'tanggal_tugas'
            : 'deadline';

        return $this->tugasDateColumn;
    }
}
