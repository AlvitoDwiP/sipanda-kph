<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenugasanRequest;
use App\Http\Requests\UpdatePenugasanRequest;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Services\PenugasanService;
use Illuminate\Http\Request;

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

        return view('pages.shared.penugasan.index', [
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

        return view('pages.shared.penugasan.create', [
            'pegawai' => $pegawai,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function show(Tugas $penugasan)
    {
        $penugasan->load(['user', 'penugasan.pegawai.user', 'penugasan.statusHistories.user']);

        return view('pages.shared.penugasan.show', [
            'penugasan' => $penugasan,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function store(StorePenugasanRequest $request, PenugasanService $penugasanService)
    {
        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_tugas' => $request->tanggal_tugas,
            'deadline' => $request->deadline,
            'prioritas' => $request->prioritas,
            'user_id' => \Auth::id(),
        ];

        $penugasanService->createPenugasan(
            $data,
            $request->pegawai_id,
            $request->file('template'),
            $this->routePrefix()
        );

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

        return view('pages.shared.penugasan.edit', [
            'penugasan' => $penugasan,
            'pegawai' => $pegawai,
            'pegawaiTerpilih' => $pegawaiTerpilih,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function update(UpdatePenugasanRequest $request, Tugas $penugasan, PenugasanService $penugasanService)
    {
        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_tugas' => $request->tanggal_tugas,
            'deadline' => $request->deadline,
            'prioritas' => $request->prioritas,
        ];

        $penugasanService->updatePenugasan(
            $penugasan,
            $data,
            $request->pegawai_id,
            $request->file('template')
        );

        return redirect()
            ->route("{$this->routePrefix()}.penugasan.index")
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(Tugas $penugasan, PenugasanService $penugasanService)
    {
        $penugasanService->deletePenugasan($penugasan);

        return redirect()
            ->route("{$this->routePrefix()}.penugasan.index")
            ->with('success', 'Penugasan berhasil dihapus.');
    }

    public function setujui(Penugasan $penugasan, PenugasanService $penugasanService)
    {
        try {
            $roleName = strtoupper($this->routePrefix());
            $penugasanService->setujuiPenugasan($penugasan, $roleName);

            return back()->with('success', 'Tugas berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function revisi(Request $request, Penugasan $penugasan, PenugasanService $penugasanService)
    {
        $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        try {
            $penugasanService->revisiPenugasan($penugasan, $request->catatan_revisi);

            return back()->with('success', 'Tugas dikembalikan untuk revisi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function batalkan(Request $request, Penugasan $penugasan, PenugasanService $penugasanService)
    {
        try {
            $penugasanService->batalkanPenugasan($penugasan, $request->input('alasan_pembatalan'));

            return back()->with('success', 'Tugas berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
