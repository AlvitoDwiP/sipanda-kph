<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        // Total Pegawai Aktif
        $totalPegawaiAktif = DB::table('pegawai')
            ->where('status_pegawai', 'aktif')
            ->count();

        // Total Tugas Aktif (baru + proses)
        $totalTugasAktif = DB::table('penugasan')
            ->whereIn('status', ['baru', 'proses'])
            ->count();

        // Total Tugas Terlambat
        $totalTugasTerlambat = DB::table('penugasan')
            ->join('tugas', 'tugas.id', '=', 'penugasan.tugas_id')
            ->whereIn('penugasan.status', ['baru', 'proses'])
            ->whereDate('tugas.deadline', '<', $today)
            ->distinct('penugasan.tugas_id')
            ->count('penugasan.tugas_id');


        $unitTerbanyak = DB::table('penugasan')
            ->join('pegawai', 'pegawai.id', '=', 'penugasan.pegawai_id')
            ->join('ref_unitkerja', 'ref_unitkerja.id', '=', 'pegawai.unitkerja_id')
            ->select('ref_unitkerja.nama_unitkerja', DB::raw('COUNT(penugasan.id) as total'))
            ->groupBy('ref_unitkerja.id', 'ref_unitkerja.nama_unitkerja')
            ->orderByDesc('total')
            ->value('nama_unitkerja');

        $statusBaru = DB::table('penugasan')
            ->where('status', 'baru')
            ->count();

        $statusProses = DB::table('penugasan')
            ->where('status', 'proses')
            ->count();

        $statusSelesai = DB::table('penugasan')
            ->where('status', 'selesai')
            ->count();

        $monitoringUnit = DB::table('ref_unitkerja')
            ->leftJoin('pegawai', 'pegawai.unitkerja_id', '=', 'ref_unitkerja.id')
            ->leftJoin('penugasan', 'penugasan.pegawai_id', '=', 'pegawai.id')
            ->leftJoin('tugas', 'tugas.id', '=', 'penugasan.tugas_id')
            ->select(
                'ref_unitkerja.id',
                'ref_unitkerja.nama_unitkerja',

                // total tugas (unik per tugas_id)
                DB::raw('COUNT(DISTINCT penugasan.tugas_id) as total'),

                // tugas selesai
                DB::raw("
            COUNT(DISTINCT CASE 
                WHEN penugasan.status = 'selesai'
                THEN penugasan.tugas_id
            END) as selesai
            "),

                // tugas terlambat (baru / proses & deadline lewat)
                DB::raw("
            COUNT(DISTINCT CASE 
                WHEN penugasan.status IN ('baru','proses')
                AND tugas.deadline < '{$today}'
                THEN penugasan.tugas_id
            END) as terlambat
            ")
            )
            ->groupBy('ref_unitkerja.id', 'ref_unitkerja.nama_unitkerja')
            ->get()
            ->map(function ($item) {
                $item->persen = $item->total > 0
                    ? round(($item->selesai / $item->total) * 100)
                    : 0;
                return $item;
            });


        return view('pages.kph.index', compact(
            'totalPegawaiAktif',
            'totalTugasAktif',
            'totalTugasTerlambat',
            'unitTerbanyak',
            'statusBaru',
            'statusProses',
            'statusSelesai',
            'monitoringUnit'
        ));
    }
}
