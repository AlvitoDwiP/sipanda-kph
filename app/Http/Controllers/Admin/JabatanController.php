<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $jabatanQuery = Jabatan::query();

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $jabatanQuery->where('nama_jabatan', 'like', "%{$q}%");
        }

        $jabatan = $jabatanQuery->get();
        return view('pages.admin.jabatan.index', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
