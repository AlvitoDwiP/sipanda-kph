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

class RiwayatKepegawaianController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatKepegawaian::all();
        return view('pages.admin.riwayat_kepegawaian.index', compact('riwayat'));
    }

    public function create()
    {
        return view('pages.admin.riwayat_kepegawaian.create', [
            'users'     => User::orderBy('name')->get(),
            'unitkerja' => UnitKerja::where('aktif', 'aktif')->orderBy('nama_unitkerja')->get(),
            'golongan'  => Golongan::where('aktif', 'aktif')->orderBy('nama_golongan')->get(),
            'jabatan'   => Jabatan::where('aktif', 'aktif')->orderBy('nama_jabatan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'unitkerja_id' => 'required|exists:ref_unitkerja,id',
            'golongan_id' => 'required|exists:ref_golongan,id',
            'jabatan_id' => 'required|exists:ref_jabatan,id',
            'tgl_mulai' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($request->user_id);

            RiwayatKepegawaian::where('user_id', $user->id)
                ->whereNull('tgl_selesai')
                ->update([
                    'tgl_selesai' => $request->tgl_mulai,
                ]);

            RiwayatKepegawaian::create([
                'user_id' => $user->id,
                'unitkerja_id' => $request->unitkerja_id,
                'golongan_id' => $request->golongan_id,
                'jabatan_id' => $request->jabatan_id,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => null,
            ]);

            $pegawai = Pegawai::where('user_id', $user->id)->first();

            if ($pegawai) {
                $pegawai->update([
                    'unitkerja_id' => $request->unitkerja_id,
                    'golongan_id' => $request->golongan_id,
                    'jabatan_id' => $request->jabatan_id,
                ]);
            } else {
                Pegawai::create([
                    'user_id' => $user->id,
                    'unitkerja_id' => $request->unitkerja_id,
                    'golongan_id' => $request->golongan_id,
                    'jabatan_id' => $request->jabatan_id,
                    'status_pegawai' => 'aktif',
                    'data_diri_id' => null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.riwayat_kepegawaian.index')
                ->with('success', 'Riwayat kepegawaian berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(RiwayatKepegawaian $riwayat)
    {
        return view('pages.admin.riwayat_kepegawaian.edit', [
            'riwayat'   => $riwayat,
            'unitkerja' => UnitKerja::where('aktif', 'aktif')->orderBy('nama_unitkerja')->get(),
            'golongan'  => Golongan::where('aktif', 'aktif')->orderBy('nama_golongan')->get(),
            'jabatan'   => Jabatan::where('aktif', 'aktif')->orderBy('nama_jabatan')->get(),
        ]);
    }

    public function update(Request $request, RiwayatKepegawaian $riwayat)
    {
        $request->validate([
            'unitkerja_id'  => 'required|exists:ref_unitkerja,id',
            'golongan_id'   => 'required|exists:ref_golongan,id',
            'jabatan_id'    => 'required|exists:ref_jabatan,id',
            'tgl_mulai'     => 'required|date',
        ]);

        DB::transaction(function () use ($request, $riwayat) {

            // 1. Update Riwayat Kepegawaian
            $riwayat->update([
                'unitkerja_id' => $request->unitkerja_id,
                'golongan_id'  => $request->golongan_id,
                'jabatan_id'   => $request->jabatan_id,
                'tgl_mulai'    => $request->tgl_mulai,
            ]);

            // 2. Update Pegawai (TIDAK SENTUH data_diri_id)
            Pegawai::where('user_id', $riwayat->user_id)
                ->update([
                    'unitkerja_id'   => $request->unitkerja_id,
                    'golongan_id'    => $request->golongan_id,
                    'jabatan_id'     => $request->jabatan_id,
                    'status_pegawai' => 'aktif',
                ]);
        });

        return redirect()
            ->route('admin.riwayat_kepegawaian.index')
            ->with('success', 'Riwayat kepegawaian berhasil diperbarui.');
    }

    public function delete(RiwayatKepegawaian $riwayat)
    {
        $riwayat->delete();

        return redirect()
            ->route('admin.riwayat_kepegawaian.index')
            ->with('success', 'Riwayat kepegawaian berhasil dihapus.');
    }
}
