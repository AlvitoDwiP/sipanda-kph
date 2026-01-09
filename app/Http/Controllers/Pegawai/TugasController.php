<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $tugas = Tugas::with([
            'penugasan.pegawai.user' // ambil semua pegawai
        ])
            ->whereHas('penugasan', function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id); // filter tugas saya
            })
            ->orderBy('deadline', 'asc')
            ->get();

        return view('pages.pegawai.tugas.index', compact('tugas'));
    }


    public function updateStatus(Request $request, Penugasan $penugasan)
    {
        $request->validate([
            'status' => 'required|in:baru,proses,selesai',
            'catatan_kepegawaian' => 'nullable|string',
        ]);

        if ($penugasan->pegawai->user_id !== auth()->id()) {
            abort(403);
        }

        $data = [
            'status' => $request->status,
        ];

        if ($request->filled('catatan_kepegawaian')) {
            $data['catatan_kepegawaian'] = $request->catatan_kepegawaian;
        }

        $penugasan->update($data);

        return response()->json([
            'success' => true,
            'status' => $penugasan->status,
        ]);
    }
}
