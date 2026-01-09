@extends('layouts.master')

@section('title', 'Data Jabatan')
@section('page-title', 'Data Jabatan')

@section('content')

{{-- NOTIFIKASI SUKSES --}}
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Jabatan</h3>

        <button type="button"
            onclick="openModal()"
            class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
            Tambah Data
        </button>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama Jabatan</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($jabatan as $i => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->nama_jabatan }}
                        </td>

                        <td class="py-4">
                            @if ($item->aktif === 'aktif')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td class="py-4 text-right">
                            <button type="button"
                                onclick="openEditModal({{ $item->id }}, '{{ $item->nama_jabatan }}', '{{ $item->aktif }}')"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </button>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_jabatan }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">
                            Data jabatan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" action="{{ route('admin.store.jabatan') }}">
            @csrf

            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="font-semibold">Tambah Jabatan</h3>
                <button type="button" onclick="closeModal()">✕</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="text-sm font-medium">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan') }}"
                        class="w-full mt-1 px-4 py-2 rounded-lg border
                        {{ $errors->has('nama_jabatan') ? 'border-red-500' : 'border-slate-200' }}" required>
                    @error('nama_jabatan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="aktif"
                        class="w-full mt-1 px-4 py-2 rounded-lg border
                        {{ $errors->has('aktif') ? 'border-red-500' : 'border-slate-200' }}" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="font-semibold">Edit Jabatan</h3>
                <button type="button" onclick="closeEditModal()">✕</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <input type="text" id="edit_nama" name="nama_jabatan"
                    class="w-full px-4 py-2 rounded-lg border border-slate-200">

                <select id="edit_aktif" name="aktif"
                    class="w-full px-4 py-2 rounded-lg border border-slate-200">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DELETE ================= --}}
<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formDelete">
            @csrf
            @method('DELETE')

            <div class="px-6 py-4 border-b flex justify-between">
                <h3 class="font-semibold">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()">✕</button>
            </div>

            <div class="px-6 py-6 text-sm">
                Hapus <strong id="deleteNama"></strong>?
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal() {
        modalToggle('modalTambah', true);
    }
    function closeModal() {
        modalToggle('modalTambah', false);
    }

    function openEditModal(id, nama, aktif) {
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_aktif').value = aktif;
        document.getElementById('formEdit').action = `/admin/jabatan/${id}`;
        modalToggle('modalEdit', true);
    }
    function closeEditModal() {
        modalToggle('modalEdit', false);
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/jabatan/${id}`;
        modalToggle('modalDelete', true);
    }
    function closeDeleteModal() {
        modalToggle('modalDelete', false);
    }

    function modalToggle(id, show) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden', !show);
        el.classList.toggle('flex', show);
    }
</script>
@endpush
