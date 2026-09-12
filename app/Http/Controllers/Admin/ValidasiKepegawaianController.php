<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanDataKepegawaian;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class ValidasiKepegawaianController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanDataKepegawaian::with(['pegawai.user', 'pegawai.unitkerja', 'pegawai.golongan', 'pegawai.jabatan', 'unitkerja', 'golongan', 'jabatan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.admin.validasi_kepegawaian.index', compact('pengajuan'));
    }

    public function process(Request $request, $id)
    {
        $pengajuan = PengajuanDataKepegawaian::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_admin' => 'nullable|string'
        ]);

        if ($request->action === 'approve') {
            // Update data pegawai
            $pegawai = $pengajuan->pegawai;
            $pegawai->update([
                'unitkerja_id' => $pengajuan->unitkerja_id,
                'golongan_id' => $pengajuan->golongan_id,
                'jabatan_id' => $pengajuan->jabatan_id,
                'status_pegawai' => $pengajuan->status_pegawai,
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
                'catatan_admin' => $request->catatan_admin
            ]);

            return redirect()->route('admin.validasi-kepegawaian.index')->with('success', 'Pengajuan berhasil disetujui.');
        } else {
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan_admin
            ]);

            return redirect()->route('admin.validasi-kepegawaian.index')->with('success', 'Pengajuan berhasil ditolak.');
        }
    }
}
