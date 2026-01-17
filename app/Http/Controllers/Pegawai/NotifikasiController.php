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
    public function index(Request $request)
    {
        // Pegawai login
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $search = $request->input('q');

        $tugasQuery = Tugas::with([
            'penugasan' => function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id);
            }
        ])
            ->whereHas('penugasan', function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id);
            })
            ->where('created_at', '>=', now()->subDays(2));

        $catatanKegiatanQuery = CatatanKegiatan::where('pegawai_id', $pegawai->id)
            ->whereIn('status', ['setuju', 'tolak'])
            ->where('created_at', '>=', now()->subDays(2));

        // Apply search filter if provided
        if ($search) {
            $tugasQuery->where(function ($query) use ($search) {
                $query->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });

            $catatanKegiatanQuery->where(function ($query) use ($search) {
                $query->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        $tugas = $tugasQuery->orderBy('deadline', 'asc')->get();
        $catatanKegiatan = $catatanKegiatanQuery->orderBy('created_at', 'desc')->get();

        return view(
            'pages.pegawai.notifikasi.index',
            compact('tugas', 'catatanKegiatan', 'search')
        );
    }
}
