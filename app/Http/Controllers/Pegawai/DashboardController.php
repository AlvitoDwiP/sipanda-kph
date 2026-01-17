<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\CatatanKegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Dapatkan ID pegawai dari user saat ini
        $pegawaiId = $user->pegawai->id ?? null;

        if (!$pegawaiId) {
            return view('pages.pegawai.index', [
                'stats' => [
                    'jumlah_tugas' => 0,
                    'tugas_belum_selesai' => 0,
                    'catatan_bulan_ini' => 0
                ],
                'progress_data' => [
                    'persentase_selesai' => 0,
                    'jumlah_selesai' => 0,
                    'jumlah_pending' => 0
                ],
                'tugas_aktif' => collect([])
            ]);
        }

        // Hitung statistik
        $jumlahTugas = Penugasan::where('pegawai_id', $pegawaiId)->count();
        $tugasBelumSelesai = Penugasan::where('pegawai_id', $pegawaiId)
            ->where('status', '!=', 'selesai')
            ->count();
        $catatanBulanIni = CatatanKegiatan::where('pegawai_id', $pegawaiId)
            ->where('periode_bulan', Carbon::now()->month)
            ->where('periode_tahun', Carbon::now()->year)
            ->count();

        // Hitung progress tugas
        $tugasSelesai = Penugasan::where('pegawai_id', $pegawaiId)
            ->where('status', 'selesai')
            ->count();
        $tugasPending = $jumlahTugas - $tugasSelesai;

        $persentaseSelesai = $jumlahTugas > 0 ? round(($tugasSelesai / $jumlahTugas) * 100) : 0;

        // Ambil daftar tugas aktif
        $tugasAktif = Penugasan::with(['tugas'])
            ->where('pegawai_id', $pegawaiId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.pegawai.index', [
            'stats' => [
                'jumlah_tugas' => $jumlahTugas,
                'tugas_belum_selesai' => $tugasBelumSelesai,
                'catatan_bulan_ini' => $catatanBulanIni
            ],
            'progress_data' => [
                'persentase_selesai' => $persentaseSelesai,
                'jumlah_selesai' => $tugasSelesai,
                'jumlah_pending' => $tugasPending
            ],
            'tugas_aktif' => $tugasAktif
        ]);
    }
}
