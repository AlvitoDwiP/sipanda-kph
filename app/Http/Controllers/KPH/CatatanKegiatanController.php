<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class CatatanKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $catatanQuery = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
        ]);

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $catatanQuery->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%")
                    ->orWhereHas('pegawai.user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('nip', 'like', "%{$q}%");
                    });
            });
        }

        $catatan = $catatanQuery
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.kph.catatan_kegiatan.index', compact('catatan'));
    }
}
