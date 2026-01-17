<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        $golonganQuery = Golongan::query();

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $golonganQuery->where('nama_golongan', 'like', "%{$q}%");
        }

        $golongan = $golonganQuery->get();
        return view('pages.admin.golongan.index', compact('golongan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_golongan' => 'required|string|max:255',
        ]);

        Golongan::create([
            'nama_golongan' => $request->nama_golongan,
        ]);

        return redirect()->back()->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_golongan' => 'required|string|max:255',
        ]);

        $golongan = Golongan::findOrFail($id);
        $golongan->update([
            'nama_golongan' => $request->nama_golongan,
        ]);

        return redirect()->back()->with('success', 'Golongan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $golongan = Golongan::findOrFail($id);
        $golongan->delete();

        return redirect()->back()->with('success', 'Golongan berhasil dihapus.');
    }
}
