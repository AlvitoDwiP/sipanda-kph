<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\RiwayatKepegawaian;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::with(['user', 'unitkerja', 'golongan', 'jabatan'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;

                $query->where(function ($sub) use ($q) {

                    // Cari dari tabel users
                    $sub->whereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('nip', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })

                        // Unit Kerja
                        ->orWhereHas('unitkerja', function ($u) use ($q) {
                            $u->where('nama_unitkerja', 'like', "%{$q}%");
                        })

                        // Golongan
                        ->orWhereHas('golongan', function ($g) use ($q) {
                            $g->where('nama_golongan', 'like', "%{$q}%");
                        })

                        // Jabatan
                        ->orWhereHas('jabatan', function ($j) use ($q) {
                            $j->where('nama_jabatan', 'like', "%{$q}%");
                        });
                });
            })
            ->get();

        return view('pages.admin.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pages.admin.pegawai.create', [
            'unitkerja' => UnitKerja::all(),
            'golongan'  => Golongan::all(),
            'jabatan'   => Jabatan::all(),
            'users'     => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'unitkerja_id'    => 'required|exists:ref_unitkerja,id',
            'golongan_id'     => 'required|exists:ref_golongan,id',
            'jabatan_id'      => 'required|exists:ref_jabatan,id',
            'status_pegawai'  => 'required|in:aktif,nonaktif',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            Pegawai::create([
                'user_id'        => $user->id,
                'unitkerja_id'   => $request->unitkerja_id,
                'golongan_id'    => $request->golongan_id,
                'jabatan_id'     => $request->jabatan_id,
                'status_pegawai' => $request->status_pegawai,
                'data_diri_id'   => null,
            ]);

            return redirect()
                ->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pages.admin.pegawai.edit', [
            'pegawai'  => $pegawai,
            'unitkerja' => UnitKerja::all(),
            'golongan' => Golongan::all(),
            'jabatan'  => Jabatan::all(),
            'users'    => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'unitkerja_id'   => 'required|exists:ref_unitkerja,id',
            'golongan_id'    => 'required|exists:ref_golongan,id',
            'jabatan_id'     => 'required|exists:ref_jabatan,id',
            'status_pegawai' => 'required|in:aktif,nonaktif',
        ]);

        try {
            $isMutasi =
                $pegawai->unitkerja_id != $request->unitkerja_id ||
                $pegawai->golongan_id  != $request->golongan_id ||
                $pegawai->jabatan_id   != $request->jabatan_id;

            $pegawai->update([
                'unitkerja_id'   => $request->unitkerja_id,
                'golongan_id'    => $request->golongan_id,
                'jabatan_id'     => $request->jabatan_id,
                'status_pegawai' => $request->status_pegawai,
            ]);

            return redirect()
                ->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function delete(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load([
            'user',
            'unitkerja',
            'golongan',
            'jabatan',
            'dataDiri',
        ]);

        return response()->json($pegawai);
    }
}
