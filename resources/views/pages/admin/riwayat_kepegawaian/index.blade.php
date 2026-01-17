@extends('layouts.master')

@section('title', 'Riwayat Kepegawaian')
@section('page-title', 'Riwayat Kepegawaian')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Riwayat Kepegawaian</h3>
        <a href="{{ route('admin.riwayat_kepegawaian.create') }}"
            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
            Tambah Data
        </a>
    </div>

    <!-- Table -->
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
                        <th class="pb-3 text-left">Tgl Mulai</th>
                        <th class="pb-3 text-left">Tgl Selesai</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayat as $i => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4">{{ $i + 1 }}</td>
                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->user->name ?? '-' }}
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
                            {{ \Carbon\Carbon::parse($item->tgl_mulai)->format('d-m-Y') }}
                        </td>
                        <td class="py-4">
                            {{ $item->tgl_selesai
                                ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d-m-Y')
                                : 'Aktif' }}
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 text-right">
                            <a href="{{ route('admin.riwayat_kepegawaian.edit', $item->id) }}"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->user->name ?? '-' }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            Data riwayat kepegawaian belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Delete (GLOBAL) -->
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
                Yakin ingin menghapus riwayat kepegawaian
                <strong id="deleteNama"></strong>?
                <br>
                <span class="text-slate-500">
                    Data pegawai tidak akan dihapus.
                </span>
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
        document.getElementById('formDelete').action =
            `/admin/riwayat-kepegawaian/${id}`;
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