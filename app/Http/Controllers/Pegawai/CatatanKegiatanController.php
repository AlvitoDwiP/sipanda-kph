<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatatanKegiatanController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan = CatatanKegiatan::with(['penugasan.tugas', 'verifier'])
            ->where('pegawai_id', $pegawai->id)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.pegawai.catatan_kegiatan.index', compact('catatan'));
    }

    public function createFromTugas(Penugasan $penugasan)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        if ($penugasan->pegawai_id !== $pegawai->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tugas ini tidak bisa ditambahkan catatan.');
        }

        if (!in_array($penugasan->status, ['sedang_dikerjakan', 'revisi', 'menunggu_verifikasi', 'proses'])) {
            return back()->with('error', 'Status tugas belum memungkinkan pembuatan catatan kegiatan.');
        }

        return view('pages.pegawai.catatan_kegiatan.create', [
            'penugasan' => $penugasan->load('tugas'),
        ]);
    }

    public function storeFromTugas(Request $request, Penugasan $penugasan)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        if ($penugasan->pegawai_id !== $pegawai->id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tugas ini tidak bisa ditambahkan catatan.');
        }

        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'deskripsi' => 'required|string',
            'hasil_kegiatan' => 'required|string',
            'kendala' => 'nullable|string',
            'foto_kegiatan' => 'nullable|array',
            'foto_kegiatan.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $foto) {
                $fotoPaths[] = $foto->store('catatan_kegiatan', 'public');
            }
        }

        CatatanKegiatan::create([
            'pegawai_id' => $pegawai->id,
            'penugasan_id' => $penugasan->id,
            'periode_bulan' => now()->month,
            'periode_tahun' => now()->year,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'judul' => 'Laporan: ' . ($penugasan->tugas->judul ?? 'Tugas'),
            'deskripsi' => $request->deskripsi,
            'hasil_kegiatan' => $request->hasil_kegiatan,
            'kendala' => $request->kendala,
            'status' => 'ajukan',
            'status_verifikasi' => 'menunggu_verifikasi',
            'catatan_status' => null,
            'catatan_verifikasi' => null,
            'foto_kegiatan' => $fotoPaths,
        ]);

        $penugasan->update(['status' => 'menunggu_verifikasi']);

        return redirect()->route('pegawai.tugas.show', $penugasan->tugas_id)
            ->with('success', 'Catatan kegiatan berhasil dikirim untuk verifikasi.');
    }

    public function show(CatatanKegiatan $catatan_kegiatan)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        if ($catatan_kegiatan->pegawai_id !== $pegawai->id) {
            abort(403);
        }

        return view('pages.pegawai.catatan_kegiatan.show', [
            'catatan' => $catatan_kegiatan->load(['penugasan.tugas', 'verifier']),
        ]);
    }

    public function edit(CatatanKegiatan $catatan_kegiatan)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        if ($catatan_kegiatan->pegawai_id !== $pegawai->id) {
            abort(403);
        }

        if (!$catatan_kegiatan->canBeEditedByPegawai()) {
            return back()->with('error', 'Catatan yang sudah disetujui/ditolak tidak bisa diedit.');
        }

        return view('pages.pegawai.catatan_kegiatan.edit', compact('catatan_kegiatan'));
    }

    public function update(Request $request, CatatanKegiatan $catatan_kegiatan)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        if ($catatan_kegiatan->pegawai_id !== $pegawai->id) {
            abort(403);
        }

        if (!$catatan_kegiatan->canBeEditedByPegawai()) {
            return back()->with('error', 'Catatan yang sudah disetujui/ditolak tidak bisa diedit.');
        }

        $request->validate([
            'tanggal_kegiatan' => 'required|date',
            'deskripsi' => 'required|string',
            'hasil_kegiatan' => 'required|string',
            'kendala' => 'nullable|string',
            'foto_kegiatan' => 'nullable|array',
            'foto_kegiatan.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'hapus_foto' => 'nullable|array',
        ]);

        $fotoLama = $catatan_kegiatan->foto_kegiatan ?? [];
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $foto) {
                Storage::disk('public')->delete($foto);
                $fotoLama = array_values(array_diff($fotoLama, [$foto]));
            }
        }

        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $foto) {
                $fotoLama[] = $foto->store('catatan_kegiatan', 'public');
            }
        }

        $catatan_kegiatan->update([
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'deskripsi' => $request->deskripsi,
            'hasil_kegiatan' => $request->hasil_kegiatan,
            'kendala' => $request->kendala,
            'status' => 'ajukan',
            'status_verifikasi' => 'menunggu_verifikasi',
            'catatan_verifikasi' => null,
            'catatan_status' => null,
            'diverifikasi_oleh' => null,
            'diverifikasi_at' => null,
            'foto_kegiatan' => $fotoLama,
        ]);

        if ($catatan_kegiatan->penugasan) {
            $catatan_kegiatan->penugasan->update(['status' => 'menunggu_verifikasi']);
        }

        return redirect()->route('pegawai.catatan_kegiatan.show', $catatan_kegiatan)
            ->with('success', 'Catatan kegiatan berhasil diperbarui dan dikirim ulang.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan_kegiatan = CatatanKegiatan::where('id', $id)
            ->where('pegawai_id', $pegawai->id)
            ->firstOrFail();

        if (in_array($catatan_kegiatan->status_verifikasi, ['disetujui', 'ditolak'])) {
            abort(403, 'Catatan sudah diproses dan tidak dapat dihapus');
        }

        $catatan_kegiatan->delete();

        return back()->with('success', 'Catatan kegiatan berhasil dihapus');
    }

    public function downloadPdf($id)
    {
        $pegawai = Pegawai::with('user')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $catatan = CatatanKegiatan::where('id', $id)
            ->where('pegawai_id', $pegawai->id)
            ->where('status_verifikasi', 'disetujui')
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.pegawai.catatan_kegiatan', [
            'pegawai' => $pegawai,
            'user' => $pegawai->user,
            'catatan' => $catatan,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Catatan-Kegiatan-' . $pegawai->user->name . '.pdf');
    }
}
