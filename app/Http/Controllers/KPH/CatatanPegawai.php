<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;

class CatatanKegiatanController extends Controller
{
    public function index()
    {
        $catatan = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.kph.catatan_kegiatan.index', compact('catatan'));
    }

}