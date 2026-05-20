<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\CatatanKegiatan;
use App\Models\Penugasan;
use App\Models\PenugasanStatusHistory;
use App\Models\Tugas;
use App\Models\User;
use App\Services\ActionableNotificationService;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->first();

        if (!$pegawai) {
            return view('pages.pegawai.tugas.index', [
                'tugas' => collect(),
                'pegawaiTidakTerhubung' => true,
            ]);
        }

        $tugasQuery = Tugas::with([
            'penugasan.pegawai.user'
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

    public function show(Tugas $tugas)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->first();

        if (!$pegawai) {
            return redirect()
                ->route('pegawai.tugas.index')
                ->with('error', 'Akun Anda belum terhubung dengan data pegawai.');
        }

        $penugasanSaya = $tugas->penugasan()
            ->with(['pegawai.user', 'tugas.user'])
            ->where('pegawai_id', $pegawai->id)
            ->first();

        if (!$penugasanSaya) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        return view('pages.pegawai.tugas.show', [
            'tugas' => $tugas->load('user'),
            'penugasanSaya' => $penugasanSaya,
            'catatanTerkait' => CatatanKegiatan::where('penugasan_id', $penugasanSaya->id)
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function updateStatus(Request $request, Penugasan $penugasan)
    {
        if (!$this->isOwnedByLoggedInPegawai($penugasan)) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $request->validate([
            'status' => 'required|in:baru,proses,selesai,belum_dikerjakan,sedang_dikerjakan',
            'catatan_kepegawaian' => 'nullable|string',

            // file (opsional, sesuai status)
            'foto_progres.*' => 'nullable|image|max:2048',
            'laporan' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
        ]);

        $data = [
            'status' => $request->status,
        ];

        /* ================= PROSES ================= */
        if (in_array($request->status, ['proses', 'sedang_dikerjakan']) && $request->hasFile('foto_progres')) {

            $paths = [];

            foreach ($request->file('foto_progres') as $file) {
                $paths[] = $file->store('progres_tugas', 'public');
            }

            $data['foto_progres'] = $paths;
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

    public function mulai(Penugasan $penugasan)
    {
        if (!$this->isOwnedByLoggedInPegawai($penugasan)) {
            return back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        if (!in_array($penugasan->status, ['belum_dikerjakan', 'baru'])) {
            return back()->with('error', 'Status tugas tidak valid untuk mulai dikerjakan.');
        }

        $this->changeStatus($penugasan, 'sedang_dikerjakan', 'Pegawai mulai mengerjakan tugas.');

        return back()->with('success', 'Tugas mulai dikerjakan.');
    }

    public function updateProgres(Request $request, Penugasan $penugasan)
    {
        if (!$this->isOwnedByLoggedInPegawai($penugasan)) {
            return back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        if (!in_array($penugasan->status, ['sedang_dikerjakan', 'revisi', 'proses'])) {
            return back()->with('error', 'Tugas belum dapat diperbarui progresnya.');
        }

        if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tugas ini tidak bisa diubah lagi.');
        }

        $request->validate([
            'progres_persen' => 'required|integer|min:0|max:100',
            'catatan_progres' => 'required|string',
        ]);

        $penugasan->update([
            'progres_persen' => $request->progres_persen,
            'catatan_progres' => $request->catatan_progres,
            'progres_updated_at' => now(),
        ]);

        PenugasanStatusHistory::create([
            'penugasan_id' => $penugasan->id,
            'user_id' => auth()->id(),
            'status_sebelum' => $penugasan->status,
            'status_sesudah' => $penugasan->status,
            'catatan' => 'Progres diperbarui oleh pegawai.',
        ]);

        return back()->with('success', 'Progres tugas berhasil diperbarui.');
    }

    public function kirimVerifikasi(Penugasan $penugasan)
    {
        if (!$this->isOwnedByLoggedInPegawai($penugasan)) {
            return back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        if (!in_array($penugasan->status, ['sedang_dikerjakan', 'revisi', 'proses'])) {
            return back()->with('error', 'Status tugas tidak valid untuk dikirim verifikasi.');
        }

        if ($penugasan->progres_persen < 1 || empty($penugasan->catatan_progres)) {
            return back()->with('error', 'Isi progres dan catatan progres terlebih dahulu.');
        }

        $this->changeStatus($penugasan, 'menunggu_verifikasi', 'Pegawai mengirim progres untuk verifikasi.');

        $penugasan->loadMissing('tugas.user', 'pegawai.user');
        $judulTugas = $penugasan->tugas->judul ?? '-';
        $namaPegawai = $penugasan->pegawai->user->name ?? 'Pegawai';
        $notificationService = app(ActionableNotificationService::class);

        $targets = User::query()
            ->whereIn('role', ['admin', 'kph'])
            ->where('status_akun', 'aktif')
            ->get();

        foreach ($targets as $target) {
            $prefix = $target->role === 'admin' ? 'admin' : 'kph';
            $notificationService->notifyUser(
                $target,
                'tugas_verifikasi',
                'Tugas menunggu verifikasi',
                $namaPegawai . ' mengirim progres tugas "' . $judulTugas . '" untuk verifikasi.',
                route($prefix . '.penugasan.show', $penugasan->tugas_id),
                ['penugasan_id' => $penugasan->id, 'tugas_id' => $penugasan->tugas_id],
                'wait_verify_task:' . $penugasan->id . ':' . now()->toDateString() . ':u' . $target->id
            );
        }

        return back()->with('success', 'Tugas berhasil dikirim untuk verifikasi.');
    }

    private function isOwnedByLoggedInPegawai(Penugasan $penugasan): bool
    {
        return $penugasan->pegawai->user_id === auth()->id();
    }

    private function changeStatus(Penugasan $penugasan, string $nextStatus, ?string $catatan = null): void
    {
        $statusSebelum = $penugasan->status;

        $payload = ['status' => $nextStatus];

        if ($nextStatus === 'selesai') {
            $payload['selesai_at'] = now();
        }

        $penugasan->update($payload);

        PenugasanStatusHistory::create([
            'penugasan_id' => $penugasan->id,
            'user_id' => auth()->id(),
            'status_sebelum' => $statusSebelum,
            'status_sesudah' => $nextStatus,
            'catatan' => $catatan,
        ]);
    }
}
