<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Services\ActionableNotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $catatanQuery = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'penugasan.tugas',
            'verifier',
        ]);

        if ($request->filled('status')) {
            $catatanQuery->where('status_verifikasi', $request->status);
        }

        $catatan = $catatanQuery->orderByDesc('created_at')->get();

        return view('pages.admin.catatan_kegiatan.index', compact('catatan'));
    }

    public function show(CatatanKegiatan $catatan)
    {
        return view('pages.admin.catatan_kegiatan.show', [
            'catatan' => $catatan->load(['pegawai.user', 'penugasan.tugas', 'verifier']),
        ]);
    }

    public function setujui(CatatanKegiatan $catatan)
    {
        if ($catatan->status_verifikasi !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status catatan tidak valid untuk aksi ini.');
        }

        $catatan->update([
            'status_verifikasi' => 'disetujui',
            'status' => 'setuju',
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_at' => now(),
            'catatan_verifikasi' => null,
            'catatan_status' => null,
        ]);

        if ($catatan->penugasan) {
            $catatan->penugasan->update([
                'status' => 'selesai',
                'selesai_at' => now(),
            ]);
        }

        $catatan->loadMissing('pegawai.user', 'penugasan');
        if ($catatan->pegawai?->user) {
            app(ActionableNotificationService::class)->notifyUser(
                $catatan->pegawai->user,
                'catatan_disetujui',
                'Catatan kegiatan disetujui',
                'Catatan kegiatan Anda telah disetujui.',
                $catatan->penugasan ? route('pegawai.tugas.show', $catatan->penugasan->tugas_id) : route('pegawai.catatan_kegiatan.index'),
                ['catatan_id' => $catatan->id]
            );
        }

        return back()->with('success', 'Catatan kegiatan berhasil disetujui.');
    }

    public function revisi(Request $request, CatatanKegiatan $catatan)
    {
        if ($catatan->status_verifikasi !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status catatan tidak valid untuk aksi ini.');
        }

        $request->validate([
            'catatan_verifikasi' => 'required|string',
        ]);

        $catatan->update([
            'status_verifikasi' => 'revisi',
            'status' => 'tolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'catatan_status' => $request->catatan_verifikasi,
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_at' => now(),
        ]);

        if ($catatan->penugasan) {
            $catatan->penugasan->update([
                'status' => 'revisi',
                'catatan_revisi' => $request->catatan_verifikasi,
            ]);
        }

        $catatan->loadMissing('pegawai.user', 'penugasan');
        if ($catatan->pegawai?->user) {
            app(ActionableNotificationService::class)->notifyUser(
                $catatan->pegawai->user,
                'catatan_revisi',
                'Catatan kegiatan perlu revisi',
                'Catatan kegiatan Anda diminta revisi oleh admin/KPH.',
                route('pegawai.catatan_kegiatan.show', $catatan->id),
                ['catatan_id' => $catatan->id]
            );
        }

        return back()->with('success', 'Catatan kegiatan dikembalikan untuk revisi.');
    }

    public function tolak(Request $request, CatatanKegiatan $catatan)
    {
        if ($catatan->status_verifikasi !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status catatan tidak valid untuk aksi ini.');
        }

        $request->validate([
            'catatan_verifikasi' => 'required|string',
        ]);

        $catatan->update([
            'status_verifikasi' => 'ditolak',
            'status' => 'tolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'catatan_status' => $request->catatan_verifikasi,
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_at' => now(),
        ]);

        if ($catatan->penugasan) {
            $catatan->penugasan->update([
                'status' => 'revisi',
                'catatan_revisi' => $request->catatan_verifikasi,
            ]);
        }

        $catatan->loadMissing('pegawai.user');
        if ($catatan->pegawai?->user) {
            app(ActionableNotificationService::class)->notifyUser(
                $catatan->pegawai->user,
                'catatan_revisi',
                'Catatan kegiatan ditolak',
                'Catatan kegiatan Anda ditolak dan perlu perbaikan.',
                route('pegawai.catatan_kegiatan.show', $catatan->id),
                ['catatan_id' => $catatan->id]
            );
        }

        return back()->with('success', 'Catatan kegiatan ditolak.');
    }

    public function downloadPdf($id)
    {
        $catatan = CatatanKegiatan::with([
            'pegawai.user',
            'pegawai.unitkerja',
            'pegawai.jabatan',
            'pegawai.golongan',
        ])->findOrFail($id);

        if ($catatan->status_verifikasi !== 'disetujui') {
            abort(403, 'Catatan belum disetujui');
        }

        $pdf = Pdf::loadView('pdf.admin.catatan_kegiatan', compact('catatan'))->setPaper('A4', 'portrait');

        return $pdf->download('Catatan-Kegiatan-' . $catatan->pegawai->user->name . '.pdf');
    }
}
