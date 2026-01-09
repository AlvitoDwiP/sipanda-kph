<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pegawai aktif
        $jumlahPegawai = DB::table('pegawai')->count();

        // Akun menunggu verifikasi
        $akunMenungguVerifikasi = DB::table('users')
            ->where('status_akun', 'nonaktif')
            ->count();

        // Tugas sedang berjalan
        // hitung berdasarkan tugas_id unik
        $tugasBerjalan = DB::table('penugasan')
            ->whereIn('status', ['baru', 'proses'])
            ->distinct('tugas_id')
            ->count('tugas_id');

        // Catatan menunggu validasi
        $catatanMenungguValidasi = DB::table('catatan_kegiatan')
            ->where('status', 'ajukan')
            ->count();

        $grafikTugas = DB::table('penugasan')
            ->select('status', DB::raw('COUNT(DISTINCT tugas_id) as total'))
            ->whereIn('status', ['baru', 'proses', 'selesai'])
            ->groupBy('status')
            ->pluck('total', 'status');

        $aktivitas = collect();

        // User baru mendaftar
        $userBaru = DB::table('users')
            ->where('status_akun', 'nonaktif')
            ->select('name', 'created_at')
            ->get();

        foreach ($userBaru as $user) {
            $aktivitas->push([
                'waktu'    => $user->created_at,
                'aktivitas' => 'Akun pegawai baru mendaftar',
                'pengguna' => $user->name ?? '-',
            ]);
        }

        // Tugas baru dibuat
        $tugasBaru = DB::table('tugas')
            ->select('judul', 'created_at')
            ->get();

        foreach ($tugasBaru as $tugas) {
            $aktivitas->push([
                'waktu'    => $tugas->created_at,
                'aktivitas' => 'Tugas baru dibuat: ' . $tugas->judul,
                'pengguna' => 'Admin',
            ]);
        }

        // Catatan kegiatan diajukan
        $catatanDiajukan = DB::table('catatan_kegiatan')
            ->join('pegawai', 'pegawai.id', '=', 'catatan_kegiatan.pegawai_id')
            ->join('users', 'users.id', '=', 'pegawai.user_id')
            ->where('catatan_kegiatan.status', 'ajukan')
            ->select(
                'users.name as nama_user',
                'catatan_kegiatan.created_at'
            )
            ->get();

        foreach ($catatanDiajukan as $catatan) {
            $aktivitas->push([
                'waktu'    => $catatan->created_at,
                'aktivitas' => 'Catatan kegiatan diajukan',
                'pengguna' => $catatan->nama_user,
            ]);
        }

        // Urutkan & batasi
        $aktivitas = $aktivitas
            ->sortByDesc('waktu')
            ->take(10)
            ->values();

        return view('pages.admin.index', compact(
            'jumlahPegawai',
            'akunMenungguVerifikasi',
            'tugasBerjalan',
            'catatanMenungguValidasi',
            'grafikTugas',
            'aktivitas'
        ));
    }
}
