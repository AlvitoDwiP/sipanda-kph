<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CatatanController extends Controller
{

    public function index(Request $request)
    {
        $catatanQuery = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
            'pegawai.golongan',
        ])
            ->whereIn('status', ['ajukan', 'setuju', 'tolak']);

        // 🔍 SEARCH
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

        // 🏢 FILTER UNIT KERJA
        if ($request->filled('unit')) {
            $catatanQuery->whereHas('pegawai.unitkerja', function ($q) use ($request) {
                $q->where('id', $request->unit);
            });
        }

        $catatan = $catatanQuery
            ->orderByRaw("FIELD(status, 'ajukan', 'setuju', 'tolak')")
            ->orderBy('created_at', 'desc')
            ->get();


        $unitkerja = UnitKerja::orderBy('nama_unitkerja')->get();

        return view(
            'pages.admin.catatan_kegiatan.index',
            compact('catatan', 'unitkerja')
        );
    }

    public function updateStatus(Request $request, CatatanKegiatan $catatan)
    {
        $request->validate([
            'status' => 'required|in:setuju,tolak',
            'catatan_status' => 'nullable|string',
        ]);

        $catatan->update([
            'status' => $request->status,
            'catatan_status' => $request->status === 'tolak'
                ? $request->catatan_status
                : null,
        ]);

        return response()->json(['success' => true]);
    }

    public function downloadPdf($id)
    {
        $catatan = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
            'pegawai.golongan',
        ])->findOrFail($id);

        // SECURITY: hanya boleh jika sudah disetujui
        if ($catatan->status !== 'setuju') {
            abort(403, 'Catatan belum disetujui');
        }

        $pdf = Pdf::loadView(
            'pages.admin.catatan_kegiatan.pdf',
            compact('catatan')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Catatan-Kegiatan-' .
                $catatan->pegawai->user->name . '.pdf'
        );
    }
}
