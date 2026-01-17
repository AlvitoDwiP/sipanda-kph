<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatatanKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatanQuery = CatatanKegiatan::where('pegawai_id', $pegawai->id);

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $catatanQuery->where(function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%")
                    ->orWhere('periode_bulan', 'like', "%{$q}%")
                    ->orWhere('periode_tahun', 'like', "%{$q}%");
            });
        }

        $catatan = $catatanQuery
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->get();

        return view('pages.pegawai.catatan_kegiatan.index', compact('catatan'));
    }

    public function create()
    {
        return view('pages.pegawai.catatan_kegiatan.create');
    }

    public function store(Request $request)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $aksi = $request->input('aksi');

        if ($aksi === 'batal') {
            return redirect()->route('pegawai.catatan-kegiatan.index');
        }

        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'aksi'          => 'required|in:draft,ajukan',

            // VALIDASI MULTIPLE FILE
            'foto_kegiatan'     => 'required|array|min:1',
            'foto_kegiatan.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fotoPaths = [];

        // SIMPAN SEMUA FOTO
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $foto) {
                $fotoPaths[] = $foto->store('catatan_kegiatan', 'public');
            }
        }

        CatatanKegiatan::create([
            'pegawai_id'     => $pegawai->id,
            'periode_bulan'  => $request->periode_bulan,
            'periode_tahun'  => $request->periode_tahun,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'status'         => $aksi,
            'foto_kegiatan' => $fotoPaths,

            // SIMPAN DALAM BENTUK JSON
            'catatan_status' => null,
        ]);

        return redirect()
            ->route('pegawai.catatan_kegiatan.index')
            ->with(
                'success',
                $aksi === 'draft'
                    ? 'Catatan kegiatan disimpan sebagai draft'
                    : 'Catatan kegiatan berhasil diajukan'
            );
    }

    public function edit($id)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan_kegiatan = CatatanKegiatan::where('id', $id)
            ->where('pegawai_id', $pegawai->id)
            ->firstOrFail();

        return view(
            'pages.pegawai.catatan_kegiatan.edit',
            compact('catatan_kegiatan')
        );
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan_kegiatan = CatatanKegiatan::where('id', $id)
            ->where('pegawai_id', $pegawai->id)
            ->firstOrFail();

        if (in_array($catatan_kegiatan->status, ['setuju', 'tolak'])) {
            abort(403, 'Catatan sudah diproses dan tidak dapat diubah');
        }

        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'aksi'          => 'required|in:draft,ajukan',

            'foto_kegiatan'     => 'nullable|array',
            'foto_kegiatan.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hapus_foto'        => 'nullable|array',
        ]);

        // FOTO LAMA
        $fotoLama = $catatan_kegiatan->foto_kegiatan ?? [];

        // HAPUS FOTO YANG DIPILIH
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $foto) {
                Storage::disk('public')->delete($foto);
                $fotoLama = array_values(array_diff($fotoLama, [$foto]));
            }
        }

        // TAMBAH FOTO BARU
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $foto) {
                $fotoLama[] = $foto->store('catatan_kegiatan', 'public');
            }
        }

        $catatan_kegiatan->update([
            'periode_bulan'  => $request->periode_bulan,
            'periode_tahun'  => $request->periode_tahun,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'status'         => $request->aksi,
            'foto_kegiatan'  => $fotoLama,
            'catatan_status' => null,
        ]);

        return redirect()
            ->route('pegawai.catatan_kegiatan.index')
            ->with('success', 'Catatan kegiatan berhasil diperbarui');
    }

    public function delete($id)
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan_kegiatan = CatatanKegiatan::where('id', $id)
            ->where('pegawai_id', $pegawai->id)
            ->firstOrFail();

        if (in_array($catatan_kegiatan->status, ['setuju', 'tolak'])) {
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
            ->where('status', 'setuju')
            ->firstOrFail();

        $pdf = Pdf::loadView('pages.pegawai.catatan_kegiatan.pdf', [
            'pegawai' => $pegawai,
            'user'    => $pegawai->user,
            'catatan' => $catatan,
        ])->setPaper('A4', 'portrait');

        return $pdf->download(
            'Catatan-Kegiatan-' . $pegawai->user->nama . '.pdf'
        );
    }
}
