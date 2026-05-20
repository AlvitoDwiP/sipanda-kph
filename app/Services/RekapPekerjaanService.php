<?php

namespace App\Services;

use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RekapPekerjaanService
{
    private const ALLOWED_PERIODS = ['harian', 'mingguan', 'bulanan'];

    public function resolveDateRange(array $filters): array
    {
        $periode = in_array($filters['periode'] ?? 'harian', self::ALLOWED_PERIODS, true)
            ? $filters['periode']
            : 'harian';

        if ($periode === 'bulanan') {
            $monthBase = isset($filters['bulan']) && $filters['bulan']
                ? Carbon::createFromFormat('Y-m', $filters['bulan'])->startOfMonth()
                : now()->startOfMonth();

            return [
                'periode' => $periode,
                'start' => $monthBase->copy()->startOfMonth(),
                'end' => $monthBase->copy()->endOfMonth(),
                'label' => 'Bulan ' . $monthBase->translatedFormat('F Y'),
                'tanggal' => $monthBase->toDateString(),
                'bulan' => $monthBase->format('Y-m'),
            ];
        }

        $dateBase = isset($filters['tanggal']) && $filters['tanggal']
            ? Carbon::parse($filters['tanggal'])
            : now();

        if ($periode === 'mingguan') {
            $start = $dateBase->copy()->startOfWeek(Carbon::MONDAY);
            $end = $dateBase->copy()->endOfWeek(Carbon::SUNDAY);

            return [
                'periode' => $periode,
                'start' => $start,
                'end' => $end,
                'label' => 'Minggu ' . $start->format('d-m-Y') . ' s/d ' . $end->format('d-m-Y'),
                'tanggal' => $dateBase->toDateString(),
                'bulan' => $dateBase->format('Y-m'),
            ];
        }

        return [
            'periode' => 'harian',
            'start' => $dateBase->copy()->startOfDay(),
            'end' => $dateBase->copy()->endOfDay(),
            'label' => 'Harian - ' . $dateBase->format('d-m-Y'),
            'tanggal' => $dateBase->toDateString(),
            'bulan' => $dateBase->format('Y-m'),
        ];
    }

    public function getFilterOptions(): array
    {
        return [
            'pegawai' => Pegawai::query()
                ->with('user:id,name')
                ->whereHas('user', fn (Builder $q) => $q->where('role', 'pegawai'))
                ->orderBy('id')
                ->get(),
            'unitKerja' => UnitKerja::query()->orderBy('nama_unitkerja')->get(),
            'statusTugas' => [
                'belum_dikerjakan',
                'sedang_dikerjakan',
                'menunggu_verifikasi',
                'revisi',
                'selesai',
                'dibatalkan',
            ],
            'prioritas' => ['rendah', 'sedang', 'tinggi'],
        ];
    }

    public function getRingkasanTugas(Carbon $startDate, Carbon $endDate, array $filters): array
    {
        $base = $this->basePenugasanQuery($startDate, $endDate, $filters);

        return [
            'total_tugas' => (clone $base)->count(),
            'tugas_belum_dikerjakan' => (clone $base)->where('penugasan.status', 'belum_dikerjakan')->count(),
            'tugas_sedang_dikerjakan' => (clone $base)->where('penugasan.status', 'sedang_dikerjakan')->count(),
            'tugas_menunggu_verifikasi' => (clone $base)->where('penugasan.status', 'menunggu_verifikasi')->count(),
            'tugas_revisi' => (clone $base)->where('penugasan.status', 'revisi')->count(),
            'tugas_selesai' => (clone $base)->where('penugasan.status', 'selesai')->count(),
            'tugas_dibatalkan' => (clone $base)->where('penugasan.status', 'dibatalkan')->count(),
            'tugas_terlambat' => (clone $base)
                ->whereDate('tugas.deadline', '<', now()->toDateString())
                ->whereNotIn('penugasan.status', ['selesai', 'dibatalkan'])
                ->count(),
            'tugas_prioritas_tinggi' => (clone $base)->where('tugas.prioritas', 'tinggi')->count(),
            'rata_rata_progres' => round((float) ((clone $base)->avg('penugasan.progres_persen') ?? 0), 2),
        ];
    }

    public function getRingkasanCatatan(Carbon $startDate, Carbon $endDate, array $filters): array
    {
        $base = $this->baseCatatanQuery($startDate, $endDate, $filters);

        return [
            'total_catatan' => (clone $base)->count(),
            'catatan_menunggu_verifikasi' => (clone $base)->where('status_verifikasi', 'menunggu_verifikasi')->count(),
            'catatan_disetujui' => (clone $base)->where('status_verifikasi', 'disetujui')->count(),
            'catatan_revisi' => (clone $base)->where('status_verifikasi', 'revisi')->count(),
            'catatan_ditolak' => (clone $base)->where('status_verifikasi', 'ditolak')->count(),
            'catatan_diverifikasi' => (clone $base)->whereNotNull('diverifikasi_at')->count(),
            'catatan_belum_diverifikasi' => (clone $base)->whereNull('diverifikasi_at')->count(),
            'catatan_diverifikasi_dalam_periode' => CatatanKegiatan::query()
                ->when($filters['pegawai_id'] ?? null, fn (Builder $q, $v) => $q->where('pegawai_id', $v))
                ->when($filters['unit_kerja_id'] ?? null, function (Builder $q, $v) {
                    $q->whereHas('pegawai', fn (Builder $sub) => $sub->where('unitkerja_id', $v));
                })
                ->whereNotNull('diverifikasi_at')
                ->whereBetween('diverifikasi_at', [$startDate, $endDate])
                ->count(),
        ];
    }

    public function getRekapPerPegawai(Carbon $startDate, Carbon $endDate, array $filters)
    {
        $penugasanAgg = DB::table('penugasan')
            ->join('tugas', 'tugas.id', '=', 'penugasan.tugas_id')
            ->whereBetween('tugas.tanggal_tugas', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('penugasan.status', $v))
            ->when($filters['prioritas'] ?? null, fn ($q, $v) => $q->where('tugas.prioritas', $v))
            ->selectRaw('penugasan.pegawai_id')
            ->selectRaw('COUNT(*) as total_tugas')
            ->selectRaw("SUM(CASE WHEN penugasan.status = 'selesai' THEN 1 ELSE 0 END) as tugas_selesai")
            ->selectRaw("SUM(CASE WHEN penugasan.status NOT IN ('selesai', 'dibatalkan') THEN 1 ELSE 0 END) as tugas_belum_selesai")
            ->selectRaw("SUM(CASE WHEN tugas.deadline < ? AND penugasan.status NOT IN ('selesai', 'dibatalkan') THEN 1 ELSE 0 END) as tugas_terlambat", [now()->toDateString()])
            ->groupBy('penugasan.pegawai_id');

        $catatanAgg = DB::table('catatan_kegiatan')
            ->whereBetween('tanggal_kegiatan', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('pegawai_id')
            ->selectRaw('COUNT(*) as total_catatan')
            ->selectRaw("SUM(CASE WHEN status_verifikasi = 'disetujui' THEN 1 ELSE 0 END) as catatan_disetujui")
            ->selectRaw("SUM(CASE WHEN status_verifikasi IN ('menunggu_verifikasi','revisi') THEN 1 ELSE 0 END) as catatan_revisi_menunggu")
            ->groupBy('pegawai_id');

        return Pegawai::query()
            ->join('users', 'users.id', '=', 'pegawai.user_id')
            ->leftJoin('ref_jabatan', 'ref_jabatan.id', '=', 'pegawai.jabatan_id')
            ->leftJoin('ref_unitkerja', 'ref_unitkerja.id', '=', 'pegawai.unitkerja_id')
            ->leftJoinSub($penugasanAgg, 'pa', fn ($join) => $join->on('pa.pegawai_id', '=', 'pegawai.id'))
            ->leftJoinSub($catatanAgg, 'ca', fn ($join) => $join->on('ca.pegawai_id', '=', 'pegawai.id'))
            ->when($filters['pegawai_id'] ?? null, fn (Builder $q, $v) => $q->where('pegawai.id', $v))
            ->when($filters['unit_kerja_id'] ?? null, fn (Builder $q, $v) => $q->where('pegawai.unitkerja_id', $v))
            ->select([
                'pegawai.id',
                'users.name as nama_pegawai',
                'ref_jabatan.nama_jabatan as jabatan',
                'ref_unitkerja.nama_unitkerja as unit_kerja',
                DB::raw('COALESCE(pa.total_tugas, 0) as total_tugas'),
                DB::raw('COALESCE(pa.tugas_selesai, 0) as tugas_selesai'),
                DB::raw('COALESCE(pa.tugas_belum_selesai, 0) as tugas_belum_selesai'),
                DB::raw('COALESCE(pa.tugas_terlambat, 0) as tugas_terlambat'),
                DB::raw('COALESCE(ca.total_catatan, 0) as total_catatan'),
                DB::raw('COALESCE(ca.catatan_disetujui, 0) as catatan_disetujui'),
                DB::raw('COALESCE(ca.catatan_revisi_menunggu, 0) as catatan_revisi_menunggu'),
                DB::raw('CASE WHEN COALESCE(pa.total_tugas, 0) = 0 THEN 0 ELSE ROUND((COALESCE(pa.tugas_selesai, 0) / pa.total_tugas) * 100, 2) END as persentase_selesai'),
            ])
            ->where(function (Builder $q) {
                $q->whereNotNull('pa.total_tugas')->orWhereNotNull('ca.total_catatan');
            })
            ->orderByDesc('tugas_selesai')
            ->limit(100)
            ->get();
    }

    public function getRekapPerUnitKerja(Carbon $startDate, Carbon $endDate, array $filters)
    {
        $penugasanAgg = DB::table('penugasan')
            ->join('pegawai', 'pegawai.id', '=', 'penugasan.pegawai_id')
            ->join('tugas', 'tugas.id', '=', 'penugasan.tugas_id')
            ->whereBetween('tugas.tanggal_tugas', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('penugasan.status', $v))
            ->when($filters['prioritas'] ?? null, fn ($q, $v) => $q->where('tugas.prioritas', $v))
            ->selectRaw('pegawai.unitkerja_id')
            ->selectRaw('COUNT(*) as total_tugas')
            ->selectRaw("SUM(CASE WHEN penugasan.status = 'selesai' THEN 1 ELSE 0 END) as tugas_selesai")
            ->selectRaw("SUM(CASE WHEN penugasan.status NOT IN ('selesai', 'dibatalkan') THEN 1 ELSE 0 END) as tugas_belum_selesai")
            ->selectRaw("SUM(CASE WHEN tugas.deadline < ? AND penugasan.status NOT IN ('selesai', 'dibatalkan') THEN 1 ELSE 0 END) as tugas_terlambat", [now()->toDateString()])
            ->groupBy('pegawai.unitkerja_id');

        $catatanAgg = DB::table('catatan_kegiatan')
            ->join('pegawai', 'pegawai.id', '=', 'catatan_kegiatan.pegawai_id')
            ->whereBetween('catatan_kegiatan.tanggal_kegiatan', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('pegawai.unitkerja_id')
            ->selectRaw('COUNT(*) as total_catatan')
            ->selectRaw("SUM(CASE WHEN catatan_kegiatan.status_verifikasi = 'disetujui' THEN 1 ELSE 0 END) as catatan_disetujui")
            ->groupBy('pegawai.unitkerja_id');

        return UnitKerja::query()
            ->leftJoinSub($penugasanAgg, 'pa', fn ($join) => $join->on('pa.unitkerja_id', '=', 'ref_unitkerja.id'))
            ->leftJoinSub($catatanAgg, 'ca', fn ($join) => $join->on('ca.unitkerja_id', '=', 'ref_unitkerja.id'))
            ->leftJoin('pegawai', 'pegawai.unitkerja_id', '=', 'ref_unitkerja.id')
            ->when($filters['unit_kerja_id'] ?? null, fn (Builder $q, $v) => $q->where('ref_unitkerja.id', $v))
            ->select([
                'ref_unitkerja.id',
                'ref_unitkerja.nama_unitkerja',
                DB::raw('COUNT(DISTINCT pegawai.id) as jumlah_pegawai'),
                DB::raw('COALESCE(pa.total_tugas, 0) as total_tugas'),
                DB::raw('COALESCE(pa.tugas_selesai, 0) as tugas_selesai'),
                DB::raw('COALESCE(pa.tugas_belum_selesai, 0) as tugas_belum_selesai'),
                DB::raw('COALESCE(pa.tugas_terlambat, 0) as tugas_terlambat'),
                DB::raw('COALESCE(ca.total_catatan, 0) as total_catatan'),
                DB::raw('COALESCE(ca.catatan_disetujui, 0) as catatan_disetujui'),
                DB::raw('CASE WHEN COALESCE(pa.total_tugas, 0) = 0 THEN 0 ELSE ROUND((COALESCE(pa.tugas_selesai, 0) / pa.total_tugas) * 100, 2) END as persentase_selesai'),
            ])
            ->groupBy([
                'ref_unitkerja.id',
                'ref_unitkerja.nama_unitkerja',
                'pa.total_tugas',
                'pa.tugas_selesai',
                'pa.tugas_belum_selesai',
                'pa.tugas_terlambat',
                'ca.total_catatan',
                'ca.catatan_disetujui',
            ])
            ->havingRaw('COALESCE(pa.total_tugas, 0) > 0 OR COALESCE(ca.total_catatan, 0) > 0')
            ->orderByDesc('total_tugas')
            ->get();
    }

    public function getDaftarTugas(Carbon $startDate, Carbon $endDate, array $filters)
    {
        return $this->basePenugasanQuery($startDate, $endDate, $filters)
            ->with(['tugas:id,judul,tanggal_tugas,deadline,prioritas', 'pegawai.user:id,name', 'pegawai.unitkerja:id,nama_unitkerja'])
            ->orderByDesc('tugas.tanggal_tugas')
            ->orderByDesc('penugasan.created_at')
            ->select('penugasan.*')
            ->paginate(20, ['*'], 'tugas_page')
            ->withQueryString();
    }

    public function getDaftarTugasForExport(Carbon $startDate, Carbon $endDate, array $filters)
    {
        return $this->basePenugasanQuery($startDate, $endDate, $filters)
            ->with(['tugas:id,judul,deskripsi,tanggal_tugas,deadline,prioritas', 'pegawai.user:id,name', 'pegawai.unitkerja:id,nama_unitkerja'])
            ->orderByDesc('tugas.tanggal_tugas')
            ->orderByDesc('penugasan.created_at')
            ->select('penugasan.*')
            ->limit(500)
            ->get();
    }

    public function getDaftarCatatan(Carbon $startDate, Carbon $endDate, array $filters)
    {
        return $this->baseCatatanQuery($startDate, $endDate, $filters)
            ->with([
                'pegawai.user:id,name',
                'pegawai.unitkerja:id,nama_unitkerja',
                'penugasan.tugas:id,judul',
                'verifier:id,name',
            ])
            ->orderByDesc('tanggal_kegiatan')
            ->orderByDesc('created_at')
            ->paginate(20, ['*'], 'catatan_page')
            ->withQueryString();
    }

    public function getDaftarCatatanForExport(Carbon $startDate, Carbon $endDate, array $filters)
    {
        return $this->baseCatatanQuery($startDate, $endDate, $filters)
            ->with([
                'pegawai.user:id,name',
                'pegawai.unitkerja:id,nama_unitkerja',
                'penugasan.tugas:id,judul',
                'verifier:id,name',
            ])
            ->orderByDesc('tanggal_kegiatan')
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();
    }

    private function basePenugasanQuery(Carbon $startDate, Carbon $endDate, array $filters): Builder
    {
        return Penugasan::query()
            ->join('tugas', 'tugas.id', '=', 'penugasan.tugas_id')
            ->join('pegawai', 'pegawai.id', '=', 'penugasan.pegawai_id')
            ->whereBetween('tugas.tanggal_tugas', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($filters['pegawai_id'] ?? null, fn (Builder $q, $v) => $q->where('penugasan.pegawai_id', $v))
            ->when($filters['unit_kerja_id'] ?? null, fn (Builder $q, $v) => $q->where('pegawai.unitkerja_id', $v))
            ->when($filters['status'] ?? null, fn (Builder $q, $v) => $q->where('penugasan.status', $v))
            ->when($filters['prioritas'] ?? null, fn (Builder $q, $v) => $q->where('tugas.prioritas', $v));
    }

    private function baseCatatanQuery(Carbon $startDate, Carbon $endDate, array $filters): Builder
    {
        return CatatanKegiatan::query()
            ->whereBetween('tanggal_kegiatan', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($filters['pegawai_id'] ?? null, fn (Builder $q, $v) => $q->where('pegawai_id', $v))
            ->when($filters['status_verifikasi'] ?? null, fn (Builder $q, $v) => $q->where('status_verifikasi', $v))
            ->when($filters['unit_kerja_id'] ?? null, function (Builder $q, $v) {
                $q->whereHas('pegawai', fn (Builder $sub) => $sub->where('unitkerja_id', $v));
            });
    }
}
