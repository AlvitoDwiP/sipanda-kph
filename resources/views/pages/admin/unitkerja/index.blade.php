@extends('layouts.master')

@section('title', 'Data Unit Kerja')
@section('page-title', 'Data Unit Kerja')

@section('content')

{{-- NOTIFIKASI SUKSES --}}
@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Unit Kerja</h3>

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
                        <th class="pb-3 text-left">Nama Unit Kerja</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($unitkerja as $i => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->nama_unitkerja }}
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
                                onclick="openEditModal({{ $item->id }}, '{{ $item->nama_unitkerja }}', '{{ $item->aktif }}')"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </button>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->nama_unitkerja }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400">
                            Data unit kerja belum tersedia
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
        <form method="POST" action="{{ route('admin.store.unitkerja') }}">
            @csrf

            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold">Tambah Unit Kerja</h3>
                <button type="button" onclick="closeModal()">✕</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="text-sm font-medium">Nama Unit Kerja</label>
                    <input type="text" name="nama_unitkerja"
                        value="{{ old('nama_unitkerja') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg" required>
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="aktif" class="w-full mt-1 px-4 py-2 border rounded-lg" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                    Simpan
                </button>
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

            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold">Edit Unit Kerja</h3>
                <button type="button" onclick="closeEditModal()">✕</button>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="text-sm font-medium">Nama Unit Kerja</label>
                    <input type="text" id="edit_nama" name="nama_unitkerja"
                        class="w-full mt-1 px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select id="edit_aktif" name="aktif"
                        class="w-full mt-1 px-4 py-2 border rounded-lg">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end px-6 py-4 border-t gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                    Update
                </button>
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

            <div class="px-6 py-4 border-b font-semibold">Konfirmasi Hapus</div>

            <div class="px-6 py-4 text-sm">
                Hapus <span id="deleteNama" class="font-semibold"></span>?
            </div>

            <div class="flex justify-end px-6 py-4 border-t gap-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal() {
        modalTambah.classList.remove('hidden');
        modalTambah.classList.add('flex');
    }

    function closeModal() {
        modalTambah.classList.add('hidden');
    }

    function openEditModal(id, nama, aktif) {
        edit_nama.value = nama;
        edit_aktif.value = aktif;
        formEdit.action = "{{ route('admin.update.unitkerja', ':id') }}".replace(':id', id);
        modalEdit.classList.remove('hidden');
        modalEdit.classList.add('flex');
    }

    function closeEditModal() {
        modalEdit.classList.add('hidden');
    }

    function openDeleteModal(id, nama) {
        deleteNama.innerText = nama;
        formDelete.action = "{{ route('admin.delete.unitkerja', ':id') }}".replace(':id', id);
        modalDelete.classList.remove('hidden');
        modalDelete.classList.add('flex');
    }

    function closeDeleteModal() {
        modalDelete.classList.add('hidden');
    }
</script>
@endpush