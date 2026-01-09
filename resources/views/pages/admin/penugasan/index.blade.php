@extends('layouts.master')

@section('title', 'Penugasan Pegawai')
@section('page-title', 'Penugasan Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Penugasan Pegawai</h3>
        <a href="{{ route('admin.penugasan.create') }}"
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
                        <th class="pb-3 text-left">Judul Tugas</th>
                        <th class="pb-3 text-left">Deadline</th>
                        <th class="pb-3 text-left">Prioritas</th>
                        <th class="pb-3 text-left">Dibuat Oleh</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($tugas as $i => $item)
                    <tr>
                        <td class="py-4">
                            {{ $i + 1 }}
                        </td>
                        <td class="py-4 font-medium text-slate-800">
                            {{ $item->judul }}
                        </td>
                        <td class="py-4">
                            {{ \Carbon\Carbon::parse($item->deadline)->format('d-m-Y') }}
                        </td>
                        <td class="py-4">
                            @if ($item->prioritas === 'rendah')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                Rendah
                            </span>
                            @elseif ($item->prioritas === 'sedang')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                Sedang
                            </span>
                            @elseif ($item->prioritas === 'tinggi')
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                Tinggi
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                                -
                            </span>
                            @endif
                        </td>

                        <td class="py-4">
                            {{ $item->user->name ?? '-' }}
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 text-right">
                            <button type="button"
                                onclick="openDetailModal({{ $item->id }})"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Detail
                            </button>

                            <span class="mx-2 text-slate-300">|</span>

                            <a href="{{ route('admin.penugasan.edit', $item->id) }}"
                                class="text-slate-600 hover:text-green-600 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>

                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->judul }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Data penugasan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold" id="detailJudul">Detail Tugas</h3>
            <button type="button" onclick="closeDetailModal()">✕</button>
        </div>
        <div class="px-6 py-6" id="detailBody">
        </div>
        <div class="px-6 py-4 border-t flex justify-end">
            <button type="button"
                onclick="closeDetailModal()"
                class="px-4 py-2 border rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Delete -->
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
                Yakin ingin menghapus tugas
                <strong id="deleteNama"></strong>?
                <br>
                <span class="text-slate-500">
                    Seluruh data penugasan pegawai akan ikut terhapus.
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
    const tugasData = @json($tugas);

    function openDetailModal(id) {
        const tugas = tugasData.find(t => t.id === id);
        if (!tugas) return;

        document.getElementById('detailJudul').innerText = tugas.judul;

        let html = `<h4 class="font-semibold text-slate-700 mb-3">Daftar Pegawai</h4>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 text-xs uppercase">
                        <th class="pb-2 text-left">Nama Pegawai</th>
                        <th class="pb-2 text-left">Status</th>
                        <th class="pb-2 text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody>`;

        if (tugas.penugasan.length > 0) {
            tugas.penugasan.forEach(p => {

                let statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600"> - </span>`;

                if (p.status === 'baru') {
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700"> Baru </span>`;
                } else if (p.status === 'proses') {
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Proses</span>`; 
                } else if (p.status === 'selesai') {
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Selesai</span>`;
                }

                html += `<tr>
                    <td class="py-2">${p.pegawai?.user?.name ?? '-'}</td>
                    <td class="py-2">${statusBadge}</td>
                    <td class="py-2">${p.catatan_kepegawaian ?? '-'}</td>
                </tr>`;
            });
        } else {
            html += `<tr>
                <td colspan="3" class="py-3 text-center text-slate-500">
                    Belum ada pegawai
                </td>
            </tr>`;
        }

        html += `</tbody></table>`;

        document.getElementById('detailBody').innerHTML = html;

        modalToggle('modalDetail', true);
    }

    function closeDetailModal() {
        modalToggle('modalDetail', false);
    }

    function openDeleteModal(id, nama) {
        document.getElementById('deleteNama').innerText = nama;
        document.getElementById('formDelete').action = `/admin/penugasan/${id}`;
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