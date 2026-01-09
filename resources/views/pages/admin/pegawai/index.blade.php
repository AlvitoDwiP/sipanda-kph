@extends('layouts.master')

@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Pegawai</h3>
        <a href="{{ route('admin.pegawai.create') }}"
            class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
            Tambah Data
        </a>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Nama</th>
                        <th class="pb-3 text-left">Unit Kerja</th>
                        <th class="pb-3 text-left">Golongan</th>
                        <th class="pb-3 text-left">Jabatan</th>
                        <th class="pb-3 text-left">Status Pegawai</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($pegawai as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                NIP: {{ $item->user->nip ?? '-' }}
                            </div>
                        </td>

                        <td class="py-4">
                            {{ $item->unitkerja->nama_unitkerja ?? '-' }}
                        </td>

                        <td class="py-4">
                            {{ $item->golongan->nama_golongan ?? '-' }}
                        </td>

                        <td class="py-4">
                            {{ $item->jabatan->nama_jabatan ?? '-' }}
                        </td>

                        <td class="py-4">
                            @if ($item->status_pegawai === 'aktif')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-600 rounded-full">
                                Nonaktif
                            </span>
                            @endif
                        </td>

                        <td class="py-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.pegawai.edit', $item->id) }}"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->user->name ?? 'Pegawai ini' }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Data pegawai belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <form method="POST" id="formDelete">
            @csrf
            @method('DELETE')

            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()">✕</button>
            </div>

            <div class="px-6 py-6 text-sm">
                Yakin ingin menghapus data pegawai
                <strong id="deleteNama"></strong>?
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">
                <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/pegawai/${id}`;
        modalToggle('modalDelete', true);
    }

    function closeDeleteModal() {
        modalToggle('modalDelete', false);
    }

    function modalToggle(id, show) {
        const modal = document.getElementById(id);
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }
</script>
@endpush