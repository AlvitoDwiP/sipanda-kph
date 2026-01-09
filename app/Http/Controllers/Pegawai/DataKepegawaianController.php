<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class DataKepegawaianController extends Controller
{
    public function index(Request $request){
        $userId = $request->user()->id;

        $pegawai = Pegawai::with(['user', 'unitkerja', 'golongan', 'jabatan', 'dataDiri'])
                    ->where('user_id', $userId)
                    ->first();

        if (!$pegawai) {
            abort(404, 'Data pegawai tidak ditemukan untuk akun ini.');
        }

        return view('pages.pegawai.data_kepegawaian.index', compact('pegawai'));
    }
}
