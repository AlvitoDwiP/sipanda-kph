<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenugasanRequest;
use App\Http\Requests\UpdatePenugasanRequest;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\PenugasanStatusHistory;
use App\Models\Tugas;
use App\Models\User;
use App\Services\ActionableNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller dasar untuk manajemen penugasan.
 *
 * Admin\PenugasanController dan KPH\PenugasanController meng-extends class ini
 * dan hanya perlu mendefinisikan routePrefix() serta viewPrefix().
 * Semua logika bisnis, query, validasi, dan transisi status berada di sini.
 */
abstract class BasePenugasanController extends Controller
{
    /**
     * Prefix route untuk role ini, mis. 'admin' atau 'kph'.
     * Digunakan untuk redirect()->route("{$this->routePrefix()}.penugasan.index").
     */
    abstract protected function routePrefix(): string;

    /**
     * Prefix view untuk role ini, mis. 'pages.admin' atau 'pages.kph'.
     * Digunakan untuk view("{$this->viewPrefix()}.penugasan.index").
     */
    

    public function index(Request $request)
    {
        $tugasQuery = Tugas::with([
            'user',
            'penugasan.pegawai.user',
        ]);

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

        return view("pages.shared.penugasan.index", [
            'tugas' => $tugas,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function create()
    {
        $pegawai = Pegawai::with('user')
            ->get()
            ->sortBy('user.name')
            ->values();

        return view("pages.shared.penugasan.create", [
            'pegawai' => $pegawai,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function show(Tugas $penugasan)
    {
        $penugasan->load(['user', 'penugasan.pegawai.user', 'penugasan.statusHistories.user']);

        return view("pages.shared.penugasan.show", [
            'penugasan'   => $penugasan,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function store(StorePenugasanRequest $request, ActionableNotificationService $notificationService)
    {
        DB::transaction(function () use ($request, $notificationService) {
            $templatePath = $request->file('template')
                ->store('template_tugas', 'public');

            $tugas = Tugas::create([
                'judul'         => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal_tugas' => $request->tanggal_tugas,
                'deadline'      => $request->deadline,
                'prioritas'     => $request->prioritas,
                'template'      => $templatePath,
                'user_id'       => auth()->id(),
            ]);

            foreach ($request->pegawai_id as $pegawaiId) {
                $penugasan = Penugasan::create([
                    'pegawai_id' => $pegawaiId,
                    'tugas_id'   => $tugas->id,
                    'status'     => 'belum_dikerjakan',
                ]);
                $notificationService->notifyTaskAssigned($penugasan, $this->routePrefix());
            }
        });

        return redirect()
            ->route("{$this->routePrefix()}.penugasan.index")
            ->with('success', 'Penugasan berhasil dibuat.');
    }

    public function edit(Tugas $penugasan)
    {
        $pegawai = Pegawai::with('user')
            ->get()
            ->sortBy('user.name')
            ->values();

        $pegawaiTerpilih = $penugasan->penugasan->pluck('pegawai_id')->toArray();

        return view("pages.shared.penugasan.edit", [
            'penugasan' => $penugasan,
            'pegawai' => $pegawai,
            'pegawaiTerpilih' => $pegawaiTerpilih,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function update(UpdatePenugasanRequest $request, Tugas $penugasan)
    {
        DB::transaction(function () use ($request, $penugasan) {
            $updateData = [
                'judul'         => $request->judul,
                'deskripsi'     => $request->deskripsi,
                'tanggal_tugas' => $request->tanggal_tugas,
                'deadline'      => $request->deadline,
                'prioritas'     => $request->prioritas,
            ];

            if ($request->hasFile('template')) {
                // Hapus template lama
                if ($penugasan->template && Storage::disk('public')->exists($penugasan->template)) {
                    Storage::disk('public')->delete($penugasan->template);
                }

                // Simpan template baru
                $updateData['template'] = $request->file('template')
                    ->store('template_tugas', 'public');
            }

            $penugasan->update($updateData);

            $pegawaiIds = $request->pegawai_id;

            Penugasan::where('tugas_id', $penugasan->id)
                ->whereNotIn('pegawai_id', $pegawaiIds)
                ->delete();

            foreach ($pegawaiIds as $pegawaiId) {
                Penugasan::firstOrCreate(
                    [
                        'tugas_id'   => $penugasan->id,
                        'pegawai_id' => $pegawaiId,
                    ],
                    ['status' => 'belum_dikerjakan']
                );
            }
        });

        return redirect()
            ->route("{$this->routePrefix()}.penugasan.index")
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(Tugas $penugasan)
    {
        DB::transaction(function () use ($penugasan) {
            Penugasan::where('tugas_id', $penugasan->id)->delete();
            $penugasan->delete();
        });

        return redirect()
            ->route("{$this->routePrefix()}.penugasan.index")
            ->with('success', 'Penugasan berhasil dihapus.');
    }

    public function setujui(Penugasan $penugasan)
    {
        if ($penugasan->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status tugas tidak valid untuk disetujui.');
        }

        $roleName = strtoupper($this->routePrefix());
        $this->changeStatus($penugasan, 'selesai', "Tugas disetujui {$roleName}.");
        $penugasan->loadMissing('pegawai.user', 'tugas');
        if ($penugasan->pegawai?->user) {
            app(ActionableNotificationService::class)->notifyUser(
                $penugasan->pegawai->user,
                'tugas_verifikasi',
                'Tugas disetujui',
                'Tugas "' . ($penugasan->tugas->judul ?? '-') . '" telah disetujui.',
                route('pegawai.tugas.show', $penugasan->tugas_id),
                ['penugasan_id' => $penugasan->id]
            );
        }

        return back()->with('success', 'Tugas berhasil disetujui.');
    }

    public function revisi(Request $request, Penugasan $penugasan)
    {
        if ($penugasan->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status tugas tidak valid untuk revisi.');
        }

        $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        $penugasan->update([
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        $this->changeStatus($penugasan, 'revisi', $request->catatan_revisi);
        $penugasan->loadMissing('pegawai.user', 'tugas');
        if ($penugasan->pegawai?->user) {
            app(ActionableNotificationService::class)->notifyUser(
                $penugasan->pegawai->user,
                'tugas_verifikasi',
                'Tugas perlu revisi',
                'Tugas "' . ($penugasan->tugas->judul ?? '-') . '" diminta revisi.',
                route('pegawai.tugas.show', $penugasan->tugas_id),
                ['penugasan_id' => $penugasan->id]
            );
        }

        return back()->with('success', 'Tugas dikembalikan untuk revisi.');
    }

    public function batalkan(Request $request, Penugasan $penugasan)
    {
        if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tugas ini tidak bisa dibatalkan.');
        }

        $penugasan->update([
            'alasan_pembatalan' => $request->input('alasan_pembatalan'),
        ]);

        $this->changeStatus($penugasan, 'dibatalkan', $request->input('alasan_pembatalan'));

        return back()->with('success', 'Tugas berhasil dibatalkan.');
    }

    protected function changeStatus(Penugasan $penugasan, string $nextStatus, ?string $catatan = null): void
    {
        $statusSebelum = $penugasan->status;

        $payload = ['status' => $nextStatus];

        if ($nextStatus === 'selesai') {
            $payload['selesai_at'] = now();
        }

        $penugasan->update($payload);

        PenugasanStatusHistory::create([
            'penugasan_id'   => $penugasan->id,
            'user_id'        => auth()->id(),
            'status_sebelum' => $statusSebelum,
            'status_sesudah' => $nextStatus,
            'catatan'        => $catatan,
        ]);
    }
}
