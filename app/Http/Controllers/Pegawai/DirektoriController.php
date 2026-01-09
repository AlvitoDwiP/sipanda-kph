<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;

class DirektoriController extends Controller
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

        return view('pages.pegawai.direktori.index', compact('pegawai'));
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