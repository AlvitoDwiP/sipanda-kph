<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

class DirektoriController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiQuery = Pegawai::with([
            'user',
            'unitkerja',
            'golongan',
            'jabatan'
        ])
            ->whereHas('user', function ($query) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');
            });

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $pegawaiQuery->where(function ($query) use ($q) {
                $query->whereHas('user', function ($userQuery) use ($q) {
                    $userQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('nip', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                })
                    ->orWhereHas('unitkerja', function ($unitQuery) use ($q) {
                        $unitQuery->where('nama_unitkerja', 'like', "%{$q}%");
                    })
                    ->orWhereHas('golongan', function ($golonganQuery) use ($q) {
                        $golonganQuery->where('nama_golongan', 'like', "%{$q}%");
                    })
                    ->orWhereHas('jabatan', function ($jabatanQuery) use ($q) {
                        $jabatanQuery->where('nama_jabatan', 'like', "%{$q}%");
                    });
            });
        }

        $pegawai = $pegawaiQuery->get();

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
