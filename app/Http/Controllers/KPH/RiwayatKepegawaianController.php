<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\RiwayatKepegawaian;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatKepegawaianController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatKepegawaian::all();
        return view('pages.kph.riwayat_kepegawaian.index', compact('riwayat'));
    }
}
