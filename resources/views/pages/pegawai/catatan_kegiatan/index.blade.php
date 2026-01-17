@extends('layouts.master')

@section('title', 'Catatan Kegiatan')
@section('page-title', 'Catatan Kegiatan')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Catatan Kegiatan</h3>
        <a href="{{ route('pegawai.catatan_kegiatan.create') }}"
            class="px-4 py-2 text-sm text-white bg-green-800 hover:bg-green-900 rounded-lg transition">
            Tambah Catatan
        </a>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Periode</th>
                        <th class="pb-3 text-left">Judul</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-left">Foto</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($catatan as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4">
                            {{ \Carbon\Carbon::create()->month($item->periode_bulan)->translatedFormat('F') }}
                            {{ $item->periode_tahun }}
                        </td>

                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->judul }}
                            </div>
                            <div class="text-xs text-slate-500 line-clamp-1">
                                {{ $item->deskripsi }}
                            </div>
                        </td>

                        <td class="py-4">
                            @if ($item->status === 'draft')
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                Draft
                            </span>
                            @elseif ($item->status === 'ajukan')
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                Diajukan
                            </span>
                            @elseif ($item->status === 'setuju')
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                Disetujui
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                Ditolak
                            </span>
                            @endif
                        </td>

                        {{-- PREVIEW FOTO --}}
                        <td class="py-4">
                            @if (is_array($item->foto_kegiatan) && count($item->foto_kegiatan))
                            <div class="flex items-center gap-2">
                                @foreach (array_slice($item->foto_kegiatan, 0, 3) as $foto)
                                <img src="{{ asset('storage/'.$foto) }}"
                                    onclick="openFotoModal({{ json_encode($item->foto_kegiatan) }})"
                                    class="w-10 h-10 object-cover rounded cursor-pointer border hover:opacity-80">
                                @endforeach

                                @if (count($item->foto_kegiatan) > 3)
                                <span class="text-xs text-slate-500">
                                    +{{ count($item->foto_kegiatan) - 3 }}
                                </span>
                                @endif
                            </div>
                            @else
                            <span class="text-xs text-slate-400">Tidak ada</span>
                            @endif
                        </td>

                        <td class="py-4 text-right whitespace-nowrap">
                            @if ($item->status === 'setuju')

                            <a href="{{ route('pegawai.catatan_kegiatan.pdf', $item->id) }}"
                                target="_blank"
                                class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition">
                                PDF
                            </a>
                            @endif

                            {{-- Jika ditolak, tampilkan tombol lihat alasan --}}
                            @if ($item->status === 'tolak')
                            <button type="button"
                                onclick="openAlasanModal(`{{ $item->catatan_status ?? '-' }}`)"
                                class="text-blue-600 hover:text-blue-800 font-medium transition">
                                Lihat Alasan
                            </button>

                            <span class="mx-2 text-slate-300">|</span>
                            @endif

                            {{-- Edit hanya jika bukan setuju --}}
                            @if (!in_array($item->status, ['setuju']))
                            <a href="{{ route('pegawai.catatan_kegiatan.edit', $item->id) }}"
                                class="text-slate-600 hover:text-green-800 font-medium transition">
                                Edit
                            </a>

                            <span class="mx-2 text-slate-300">|</span>
                            @endif

                            {{-- Hapus hanya jika draft / tolak --}}
                            @if (in_array($item->status, ['draft', 'tolak']))
                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ $item->judul }}')"
                                class="text-slate-600 hover:text-red-600 font-medium transition">
                                Hapus
                            </button>
                            @endif

                            @if(in_array($item->status, ['setuju']))
                            <span class="mx-2 text-slate-300">|</span>

                            <small class="inline-flex items-center text-emerald-600 font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Telah Disetujui
                            </small>
                            @endif

                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            Catatan kegiatan belum tersedia
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
                Yakin ingin menghapus catatan kegiatan
                <strong id="deleteJudul"></strong>?
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

<!-- MODAL ALASAN PENOLAKAN -->
<div id="modalAlasan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">

        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-red-600">Alasan Penolakan</h3>
            <button type="button" onclick="closeAlasanModal()">✕</button>
        </div>

        <div class="px-6 py-6 text-sm text-slate-700 whitespace-pre-line">
            <p id="isiAlasan"></p>
        </div>

        <div class="px-6 py-4 border-t text-right">
            <button type="button"
                onclick="closeAlasanModal()"
                class="px-4 py-2 border rounded-lg">
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- MODAL FOTO -->
<div id="modalFoto"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70">

    <div class="bg-white w-full max-w-5xl max-h-[90vh] rounded-xl shadow-lg flex flex-col">

        <!-- HEADER -->
        <div class="px-4 py-3 border-b flex justify-between items-center">
            <h3 class="font-semibold text-slate-700">Foto Kegiatan</h3>
            <button onclick="closeFotoModal()"
                class="text-slate-600 hover:text-red-600 text-lg">
                ✕
            </button>
        </div>

        <!-- BODY (SCROLL AREA) -->
        <div id="fotoContainer"
            class="p-6 overflow-y-auto flex flex-col items-center gap-6">
        </div>

    </div>
</div>


@endsection

@push('scripts')
<script>
    function openDeleteModal(id, judul) {
        document.getElementById('deleteJudul').innerText = judul;
        document.getElementById('formDelete').action =
            `/pegawai/catatan-kegiatan/${id}`;
        toggleModal(true);
    }

    function closeDeleteModal() {
        toggleModal(false);
    }

    function toggleModal(show) {
        const modal = document.getElementById('modalDelete');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

<script>
    function openAlasanModal(alasan) {
        document.getElementById('isiAlasan').innerText = alasan || '-';
        toggleAlasan(true);
    }

    function closeAlasanModal() {
        toggleAlasan(false);
    }

    function toggleAlasan(show) {
        const modal = document.getElementById('modalAlasan');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>

<script>
    function openFotoModal(fotos) {
        const container = document.getElementById('fotoContainer');
        container.innerHTML = '';

        fotos.forEach(foto => {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center justify-center';

            const img = document.createElement('img');
            img.src = `/storage/${foto}`;
            img.className = `
                max-w-full
                max-h-[70vh]
                object-contain
                rounded-lg
                border
                shadow
            `;

            wrapper.appendChild(img);
            container.appendChild(wrapper);
        });

        const modal = document.getElementById('modalFoto');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeFotoModal() {
        const modal = document.getElementById('modalFoto');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

@endpush