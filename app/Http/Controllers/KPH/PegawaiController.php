<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Shared\BasePegawaiQrController;
use App\Models\Pegawai;
use Illuminate\Http\Request;

/**
 * Controller pegawai untuk role KPH.
 * KPH bersifat read-only (index + show) + manajemen QR.
 * Operasi QR diwarisi dari BasePegawaiQrController.
 */
class PegawaiController extends BasePegawaiQrController
{
    protected function routePrefix(): string
    {
        return 'kph';
    }

    public function index(Request $request)
    {
        $pegawai = Pegawai::with([
            'user',
            'unitkerja',
            'golongan',
            'jabatan',
        ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');
            })
            ->search($request->q)
            ->get();

        return view('pages.kph.pegawai.index', compact('pegawai'));
    }

    public function show($id)
    {
        $pegawai = Pegawai::with([
            'user',
            'unitkerja',
            'golongan',
            'jabatan',
            'dataDiri',
        ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');
            })
            ->find($id);

        if (! $pegawai) {
            return response()->json(['error' => 'Pegawai not found'], 404);
        }

        return response()->json($pegawai);
    }
}
