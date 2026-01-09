<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with([
                'user',
                'unitkerja',
                'golongan',
                'jabatan'
            ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');
            })
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
                'dataDiri'
            ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');
            })
            ->find($id);

        if (!$pegawai) {
            return response()->json(['error' => 'Pegawai not found'], 404);
        }

        return response()->json($pegawai);
    }
}