@extends('layouts.master')

@section('title', 'Data Golongan')
@section('page-title', 'Data Golongan')

@section('content')

{{-- NOTIFIKASI SUKSES --}}
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Golongan</h3>

        <button type="button" onclick="openModal()" class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
            Tambah Data
        </button>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama Golongan</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($golongan as $i => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>
                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->nama_golongan }}
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
                            <button type="button" onclick="openEditModal({{ $item->id }}, '{{ $item->nama_golongan }}', '{{ $item->aktif }}')" class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </button>

                            <span class="mx-2 text-slate-300">|</span>
                            <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_golongan }}')"class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">
                            Data golongan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" action="{{ route('admin.store.golongan') }}">
            @csrf

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="font-semibold text-slate-800">Tambah Golongan</h3>
                <button type="button"
                    onclick="closeModal()"
                    class="text-slate-400 hover:text-slate-600">
                    ✕
                </button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4">

                {{-- NAMA GOLONGAN --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Nama Golongan
                    </label>

                    <input type="text"
                        name="nama_golongan"
                        value="{{ old('nama_golongan') }}"
                        class="w-full mt-1 px-4 py-2 rounded-lg border
                        {{ $errors->has('nama_golongan')
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-slate-200 focus:ring-green-600' }}" required>

                    @error('nama_golongan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select name="aktif"
                        class="w-full mt-1 px-4 py-2 rounded-lg border
                        {{ $errors->has('aktif')
                            ? 'border-red-500 focus:ring-red-500'
                            : 'border-slate-200 focus:ring-green-600' }}" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('aktif') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>

                    @error('aktif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t">
                <button type="button"
                    onclick="closeModal()"
                    class="px-4 py-2 text-sm border rounded-lg text-slate-600 hover:bg-slate-100">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="font-semibold text-slate-800">Edit Golongan</h3>
                <button type="button"
                    onclick="closeEditModal()"
                    class="text-slate-400 hover:text-slate-600">
                    ✕
                </button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4">

                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Nama Golongan
                    </label>
                    <input type="text"
                        id="edit_nama"
                        name="nama_golongan"
                        class="w-full mt-1 px-4 py-2 rounded-lg border border-slate-200 focus:ring-green-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Status
                    </label>
                    <select name="aktif"
                        id="edit_aktif"
                        class="w-full mt-1 px-4 py-2 rounded-lg border border-slate-200 focus:ring-green-600">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t">
                <button type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 text-sm border rounded-lg text-slate-600 hover:bg-slate-100">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">

        <form method="POST" id="formDelete">
            @csrf
            @method('DELETE')

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="font-semibold text-slate-800">Konfirmasi Hapus</h3>
                <button type="button"
                    onclick="closeDeleteModal()"
                    class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-6 text-sm text-slate-600">
                Apakah Anda yakin ingin menghapus
                <span id="deleteNama" class="font-semibold text-slate-800"></span>?
                <br>
                <span class="text-red-600 font-medium">Data tidak dapat dikembalikan.</span>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t">
                <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 text-sm border rounded-lg text-slate-600 hover:bg-slate-100">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal() {
        const modal = document.getElementById('modalTambah');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('modalTambah');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openEditModal(id, nama, aktif) {
        const modal = document.getElementById('modalEdit');

        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_aktif').value = aktif;

        document.getElementById('formEdit').action =
            `/admin/golongan/${id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

        function openDeleteModal(id, nama) {
        const modal = document.getElementById('modalDelete');

        document.getElementById('deleteNama').innerText = nama;

        document.getElementById('formDelete').action =
            "{{ route('admin.delete.golongan', ':id') }}".replace(':id', id);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modalDelete');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush