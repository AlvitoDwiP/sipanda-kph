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
    public function index(Request $request)
    {
        $tugasQuery = Tugas::with(['user', 'penugasan.pegawai.user']);

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $tugasQuery->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%")
                      ->orWhere('prioritas', 'like', "%{$q}%")
                      ->orWhereHas('user', function ($userQuery) use ($q) {
                          $userQuery->where('name', 'like', "%{$q}%")
                                   ->orWhere('email', 'like', "%{$q}%")
                                   ->orWhere('nip', 'like', "%{$q}%");
                      });
            });
        }

        $tugas = $tugasQuery->orderBy('created_at', 'desc')->get();
        return view('pages.kph.penugasan.index', compact('tugas'));
    }
}