<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['user', 'penugasan.pegawai.user'])->orderBy('created_at', 'desc')->get();
        return view('pages.kph.penugasan.index', compact('tugas'));
    }
}