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
    public function index()
    {
        $pegawai = Pegawai::with(['user', 'unitkerja', 'golongan', 'jabatan'])->get();
        return view('pages.admin.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pages.admin.pegawai.create', [
            'unitkerja' => UnitKerja::where('aktif', 'aktif')->orderBy('nama_unitkerja')->get(),
            'golongan'  => Golongan::where('aktif', 'aktif')->orderBy('nama_golongan')->get(),
            'jabatan'   => Jabatan::where('aktif', 'aktif')->orderBy('nama_jabatan')->get(),
            'users'     => User::orderBy('name')->get(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'unitkerja_id' => 'required|exists:ref_unitkerja,id',
            'golongan_id' => 'required|exists:ref_golongan,id',
            'jabatan_id' => 'required|exists:ref_jabatan,id',
            'status_pegawai' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Ambil user (validasi tambahan)
            $user = User::findOrFail($request->user_id);

            // 2️⃣ Simpan pegawai
            $pegawai = Pegawai::create([
                'user_id'        => $user->id,
                'unitkerja_id'   => $request->unitkerja_id,
                'golongan_id'    => $request->golongan_id,
                'jabatan_id'     => $request->jabatan_id,
                'status_pegawai' => $request->status_pegawai,
                'data_diri_id'   => null,
            ]);

            // 3️⃣ Simpan riwayat kepegawaian pertama
            RiwayatKepegawaian::create([
                'user_id' => $user->id,
                'unitkerja_id' => $request->unitkerja_id,
                'golongan_id' => $request->golongan_id,
                'jabatan_id' => $request->jabatan_id,
                'tgl_mulai' => $pegawai->created_at->toDateString(),
                'tgl_selesai' => null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.pegawai.index')
                ->with('success', 'Pegawai & riwayat kepegawaian berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pages.admin.pegawai.edit', [
            'pegawai'  => $pegawai,
            'unitkerja' => UnitKerja::where('aktif', 'aktif')->orderBy('nama_unitkerja')->get(),
            'golongan' => Golongan::where('aktif', 'aktif')->orderBy('nama_golongan')->get(),
            'jabatan'  => Jabatan::where('aktif', 'aktif')->orderBy('nama_jabatan')->get(),
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

        DB::beginTransaction();

        try {
            // 1️⃣ Deteksi perubahan (mutasi)
            $isMutasi =
                $pegawai->unitkerja_id != $request->unitkerja_id ||
                $pegawai->golongan_id  != $request->golongan_id ||
                $pegawai->jabatan_id   != $request->jabatan_id;

            // 2️⃣ Update data pegawai
            $pegawai->update([
                'unitkerja_id'   => $request->unitkerja_id,
                'golongan_id'    => $request->golongan_id,
                'jabatan_id'     => $request->jabatan_id,
                'status_pegawai' => $request->status_pegawai,
            ]);

            // 3️⃣ Jika mutasi → kelola riwayat kepegawaian
            if ($isMutasi) {

                // Tutup riwayat aktif lama
                RiwayatKepegawaian::where('user_id', $pegawai->user_id)
                    ->whereNull('tgl_selesai')
                    ->update([
                        'tgl_selesai' => now()->toDateString(),
                    ]);

                // Tambah riwayat baru
                RiwayatKepegawaian::create([
                    'user_id' => $pegawai->user_id,
                    'unitkerja_id' => $request->unitkerja_id,
                    'golongan_id' => $request->golongan_id,
                    'jabatan_id' => $request->jabatan_id,
                    'tgl_mulai' => now()->toDateString(),
                    'tgl_selesai' => null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

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
}
