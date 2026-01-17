<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $tugasQuery = Tugas::with([
            'penugasan.pegawai.user' // ambil semua pegawai
        ])
            ->whereHas('penugasan', function ($q) use ($pegawai) {
                $q->where('pegawai_id', $pegawai->id); // filter tugas saya
            });

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $tugasQuery->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('prioritas', 'like', "%{$q}%");
            });
        }

        $tugas = $tugasQuery->orderBy('deadline', 'asc')->get();

        return view('pages.pegawai.tugas.index', compact('tugas'));
    }

    public function updateStatus(Request $request, Penugasan $penugasan)
    {
        if ($penugasan->pegawai->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:baru,proses,selesai',
            'catatan_kepegawaian' => 'nullable|string',

            // file (opsional, sesuai status)
            'foto_progres.*' => 'nullable|image|max:2048',
            'laporan' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
        ]);

        $data = [
            'status' => $request->status,
        ];

        /* ================= PROSES ================= */
        if ($request->status === 'proses' && $request->hasFile('foto_progres')) {

            $paths = [];

            foreach ($request->file('foto_progres') as $file) {
                $paths[] = $file->store('progres_tugas', 'public');
            }

            // simpan sebagai JSON (TIDAK mengubah blade)
            $data['foto_progres'] = json_encode($paths);
        }

        /* ================= SELESAI ================= */
        if ($request->status === 'selesai') {

            if ($request->filled('catatan_kepegawaian')) {
                $data['catatan_kepegawaian'] = $request->catatan_kepegawaian;
            }

            if ($request->hasFile('laporan')) {
                $data['laporan'] = $request->file('laporan')
                    ->store('laporan_tugas', 'public');
            }
        }

        $penugasan->update($data);

        return response()->json([
            'success' => true,
            'status'  => $penugasan->status,
        ]);
    }
}
