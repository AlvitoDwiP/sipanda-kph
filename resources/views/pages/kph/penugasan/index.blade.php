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
                        <td class="py-4 capitalize">
                            {{ $item->prioritas }}
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

        let html = ``;

        html += `
            <div class="mb-5">
                <strong class="text-sm text-slate-700 block mb-2">Template Tugas</strong>
                ${
                    tugas.template
                        ? `<a href="/storage/${tugas.template}" target="_blank"
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm
                                   text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                            Unduh Template
                          </a>`
                        : `<span class="text-slate-400 text-sm">Tidak ada template</span>`
                }
            </div>
        `;

        html += `
            <h4 class="font-semibold text-slate-700 mb-3">Daftar Pegawai</h4>
            <table class="w-full text-sm border rounded-lg overflow-hidden">
                <thead class="bg-slate-50">
                    <tr class="text-slate-500 text-xs uppercase">
                        <th class="px-3 py-2 text-left">Nama</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
        `;

        if (tugas.penugasan.length > 0) {
            tugas.penugasan.forEach(p => {

                let badge =
                    p.status === 'selesai' ?
                    `<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Selesai</span>` :
                    p.status === 'proses' ?
                    `<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Proses</span>` :
                    `<span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Baru</span>`;

                html += `
                    <tr>
                        <td class="px-3 py-2">${p.pegawai?.user?.name ?? '-'}</td>
                        <td class="px-3 py-2">${badge}</td>
                        <td class="px-3 py-2">${p.catatan_kepegawaian ?? '-'}</td>
                    </tr>
                `;

                html += `
                    <tr>
                        <td colspan="3" class="px-3 py-3 bg-slate-50">
                            <strong class="text-sm block mb-2">Laporan</strong>
                            ${
                                p.laporan
                                    ? `<a href="/storage/${p.laporan}" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-2 text-sm
                                               text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                        Lihat Laporan
                                      </a>`
                                    : `<span class="text-slate-400 text-sm">Belum ada laporan</span>`
                            }
                        </td>
                    </tr>
                `;

                let fotoHtml = '';
                if (p.foto_progres) {
                    try {
                        const fotos = JSON.parse(p.foto_progres);
                        fotos.forEach(f => {
                            fotoHtml += `
                                <img src="/storage/${f}"
                                     class="w-full h-20 object-cover rounded border"
                                     alt="Foto Progres">
                            `;
                        });
                    } catch (e) {}
                }

                html += `
                    <tr>
                        <td colspan="3" class="px-3 py-3">
                            <strong class="text-sm block mb-2">Foto Progres</strong>
                            ${
                                fotoHtml
                                    ? `<div class="grid grid-cols-4 gap-2">${fotoHtml}</div>`
                                    : `<span class="text-slate-400 text-sm">Belum ada foto progres</span>`
                            }
                        </td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="3" class="px-3 py-4 text-center text-slate-500">
                        Belum ada pegawai
                    </td>
                </tr>
            `;
        }

        html += `
                </tbody>
            </table>
        `;

        document.getElementById('detailBody').innerHTML = html;
        modalToggle('modalDetail', true);
    }

    function closeDetailModal() {
        modalToggle('modalDetail', false);
    }

    function modalToggle(id, show) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    }
</script>
@endpush