<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class CatatanKegiatanController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::where('user_id', auth()->id())->firstOrFail();

        $catatan = CatatanKegiatan::where('pegawai_id', $pegawai->id)
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

        // tombol yang ditekan: draft | ajukan
        $aksi = $request->input('aksi');

        if ($aksi === 'batal') {
            return redirect()
                ->route('pegawai.catatan-kegiatan.index');
        }

        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'aksi'          => 'required|in:draft,ajukan',
        ]);

        CatatanKegiatan::create([
            'pegawai_id'    => $pegawai->id,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'status'        => $aksi, 
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

        // Tidak boleh edit jika sudah diproses
        if (in_array($catatan_kegiatan->status, ['setuju', 'tolak'])) {
            abort(403, 'Catatan sudah diproses dan tidak dapat diubah');
        }
        
        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer',
            'judul'         => 'required|string',
            'deskripsi'     => 'required|string',
            'aksi'        => 'required|in:draft,ajukan',
        ]);
        
        $catatan_kegiatan->update([
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'status'        => $request->aksi,
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
}
