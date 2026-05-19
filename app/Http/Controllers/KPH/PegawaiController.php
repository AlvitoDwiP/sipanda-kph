<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::with([
            'user',
            'unitkerja',
            'golongan',
            'jabatan'
        ])
            ->whereHas('user', function ($query) use ($request) {
                $query->where('role', 'pegawai')
                    ->where('status_akun', 'aktif');

                // 🔍 SEARCH (nama, nip, email)
                if ($request->filled('q')) {
                    $query->where(function ($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->q . '%')
                            ->orWhere('nip', 'like', '%' . $request->q . '%')
                            ->orWhere('email', 'like', '%' . $request->q . '%');
                    });
                }
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;

                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('unitkerja', function ($u) use ($q) {
                        $u->where('nama_unitkerja', 'like', "%{$q}%");
                    })
                        ->orWhereHas('golongan', function ($g) use ($q) {
                            $g->where('nama_golongan', 'like', "%{$q}%");
                        })
                        ->orWhereHas('jabatan', function ($j) use ($q) {
                            $j->where('nama_jabatan', 'like', "%{$q}%");
                        });
                });
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

    public function generateQr(Pegawai $pegawai)
    {
        if (!$pegawai->isAktif()) {
            return back()->with('error', 'QR tidak bisa dibuat karena pegawai tidak aktif.');
        }

        if ($pegawai->hasQrToken()) {
            return back()->with('error', 'Pegawai sudah memiliki token QR.');
        }

        $pegawai->ensureQrToken();

        return back()->with('success', 'Token QR pegawai berhasil dibuat.');
    }

    public function regenerateQr(Pegawai $pegawai)
    {
        if (!$pegawai->isAktif()) {
            return back()->with('error', 'QR tidak bisa dibuat karena pegawai tidak aktif.');
        }

        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $pegawai->regenerateQrToken();

        return back()->with('success', 'Token QR pegawai berhasil diperbarui.');
    }

    public function cetakQr(Pegawai $pegawai)
    {
        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $pegawai->load(['user', 'unitkerja', 'jabatan']);

        return view('pages.admin.pegawai.qr_cetak', [
            'pegawai' => $pegawai,
            'routePrefix' => 'kph',
        ]);
    }

    public function downloadQr(Pegawai $pegawai)
    {
        if (!$pegawai->hasQrToken()) {
            return back()->with('error', 'QR pegawai belum tersedia.');
        }

        $svg = QrCode::format('svg')->size(500)->margin(1)->generate($pegawai->qr_token);
        $filename = 'qr-pegawai-' . $pegawai->id . '.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
