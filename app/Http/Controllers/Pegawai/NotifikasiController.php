<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        // Pegawai login
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $tugas = Tugas::with([
            'penugasan' => function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id);
            }
        ])
            ->whereHas('penugasan', function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id);
            })
            ->where('created_at', '>=', now()->subDays(2))
            ->orderBy('deadline', 'asc')
            ->get();

        $catatanKegiatan = CatatanKegiatan::where('pegawai_id', $pegawai->id)
            ->whereIn('status', ['setuju', 'tolak'])
            ->where('created_at', '>=', now()->subDays(2))
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'pages.pegawai.notifikasi.index',
            compact('tugas', 'catatanKegiatan')
        );
    }
}
