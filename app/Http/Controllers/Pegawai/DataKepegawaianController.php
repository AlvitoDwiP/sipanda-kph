<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\PengajuanDataKepegawaian;
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

        if (! $pegawai) {
            abort(404, 'Data pegawai tidak ditemukan untuk akun ini.');
        }

        // DATA DROPDOWN
        $unitkerjaList = UnitKerja::orderBy('nama_unitkerja')->get();
        $golonganList = Golongan::orderBy('nama_golongan')->get();
        $jabatanList = Jabatan::orderBy('nama_jabatan')->get();

        $pengajuanAktif = PengajuanDataKepegawaian::where('pegawai_id', $pegawai->id)
            ->where('status', 'menunggu_verifikasi')
            ->first();

        return view(
            'pages.pegawai.data_kepegawaian.index',
            compact('pegawai', 'unitkerjaList', 'golonganList', 'jabatanList', 'pengajuanAktif')
        );
    }

    public function updateKepegawaian(Request $request, Pegawai $pegawai)
    {
        // Pastikan hanya user yang bersangkutan yang bisa mengajukan
        if ($pegawai->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak diizinkan mengubah data ini.');
        }

        $validated = $request->validate([
            'unitkerja_id' => 'required|exists:ref_unitkerja,id',
            'golongan_id' => 'required|exists:ref_golongan,id',
            'jabatan_id' => 'required|exists:ref_jabatan,id',
            'status_pegawai' => 'required|in:aktif,nonaktif',
        ]);

        $pengajuanAktif = PengajuanDataKepegawaian::where('pegawai_id', $pegawai->id)
            ->where('status', 'menunggu_verifikasi')
            ->first();

        if ($pengajuanAktif) {
            return redirect()->back()->with('error', 'Anda masih memiliki pengajuan perubahan data yang sedang menunggu verifikasi.');
        }

        $pengajuan = PengajuanDataKepegawaian::create([
            'pegawai_id' => $pegawai->id,
            'unitkerja_id' => $validated['unitkerja_id'],
            'golongan_id' => $validated['golongan_id'],
            'jabatan_id' => $validated['jabatan_id'],
            'status_pegawai' => $validated['status_pegawai'],
            'status' => 'menunggu_verifikasi',
        ]);

        // Send notification to Admin and Super Admin
        $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PengajuanKepegawaianBaru($pengajuan));

        return redirect()->back()->with('success', 'Data pengajuan perubahan berhasil dikirim, tunggu verifikasi admin.');
    }
}
