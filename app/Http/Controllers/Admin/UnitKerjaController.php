<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unitkerja = UnitKerja::all();
        return view('pages.admin.unitkerja.index', compact('unitkerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unitkerja' => 'required|string|max:255',
            'aktif' => 'required|string|max:50',
        ]);

        UnitKerja::create([
            'nama_unitkerja' => $request->nama_unitkerja,
            'aktif' => $request->aktif,
        ]);

        return redirect()->back()->with('success', 'Unit Kerja berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_unitkerja' => 'required|string|max:255',
            'aktif' => 'required|string|max:50',
        ]);

        $unitkerja = UnitKerja::findOrFail($id);
        $unitkerja->update([
            'nama_unitkerja' => $request->nama_unitkerja,
            'aktif' => $request->aktif,
        ]);

        return redirect()->back()->with('success', 'Unit Kerja berhasil diperbarui.');
    }

    public function delete($id)
    {
        $unitkerja = UnitKerja::findOrFail($id);
        $unitkerja->delete();

        return redirect()->back()->with('success', 'Unit Kerja berhasil dihapus.');
    }
}
