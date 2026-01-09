<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\DataDiri;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataDiriController extends Controller
{
    public function index()
    {
        $pegawai = $this->pegawaiLogin();

        return view('pages.pegawai.data_diri.index', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, true);

        DB::transaction(function () use ($request) {

            $pegawai = $this->pegawaiLogin();

            if ($pegawai->data_diri_id) {
                abort(400, 'Pegawai sudah memiliki data diri.');
            }

            $fotoPath = $request->file('foto')->store('foto_diri', 'public');

            $dataDiri = DataDiri::create([
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'foto' => $fotoPath,
            ]);

            $pegawai->update([
                'data_diri_id' => $dataDiri->id
            ]);
        });

        return redirect()
            ->route('pegawai.data_diri.index')
            ->with('success', 'Data Diri berhasil disimpan.');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);

        DB::transaction(function () use ($request) {

            $pegawai = $this->pegawaiLogin();

            if (!$pegawai->dataDiri) {
                abort(404, 'Data Diri tidak ditemukan.');
            }

            $dataDiri = $pegawai->dataDiri;

            if ($request->hasFile('foto')) {
                if ($dataDiri->foto && Storage::disk('public')->exists($dataDiri->foto)) {
                    Storage::disk('public')->delete($dataDiri->foto);
                }

                $dataDiri->foto = $request->file('foto')->store('foto_diri', 'public');
            }

            $dataDiri->update([
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
        });

        return redirect()
            ->route('pegawai.data_diri.index')
            ->with('success', 'Data Diri berhasil diperbarui.');
    }
    
    private function pegawaiLogin(): Pegawai
    {
        return Pegawai::with('dataDiri')
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    private function validateRequest(Request $request, bool $isCreate = false): void
    {
        $request->validate([
            'no_hp' => ['required', 'string', 'max:15', 'regex:/^\d+$/'],
            'alamat' => ['required', 'string', 'max:500'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tgl_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'foto' => [$isCreate ? 'required' : 'nullable', 'image', 'max:2048'],
        ], [
            'no_hp.regex' => 'No HP harus berupa angka saja.',
        ]);
    }
}
