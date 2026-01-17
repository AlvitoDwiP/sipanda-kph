<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class DataKepegawaianController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $pegawai = Pegawai::with(['user', 'unitkerja', 'golongan', 'jabatan', 'dataDiri'])
            ->where('user_id', $userId)
            ->first();

        if (!$pegawai) {
            abort(404, 'Data pegawai tidak ditemukan untuk akun ini.');
        }

        // DATA DROPDOWN
        $unitkerjaList = UnitKerja::orderBy('nama_unitkerja')->get();
        $golonganList  = Golongan::orderBy('nama_golongan')->get();
        $jabatanList   = Jabatan::orderBy('nama_jabatan')->get();

        return view(
            'pages.pegawai.data_kepegawaian.index',
            compact('pegawai', 'unitkerjaList', 'golonganList', 'jabatanList')
        );
    }

    public function updateKepegawaian(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'unitkerja_id'   => 'required|exists:ref_unitkerja,id',
            'golongan_id'    => 'required|exists:ref_golongan,id',
            'jabatan_id'     => 'required|exists:ref_jabatan,id',
            'status_pegawai' => 'required|in:aktif,nonaktif',
        ]);

        $pegawai->update($request->only([
            'unitkerja_id',
            'golongan_id',
            'jabatan_id',
            'status_pegawai'
        ]));

        return back()->with('success', 'Data kepegawaian berhasil diperbarui');
    }
}
