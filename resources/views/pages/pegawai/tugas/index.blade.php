@extends('layouts.master')

@section('title', 'Tugas Saya')
@section('page-title', 'Tugas Saya')

@section('content')

@if (session('error'))
<div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 text-sm">
    {{ session('error') }}
</div>
@endif

@if (!empty($pegawaiTidakTerhubung) && $pegawaiTidakTerhubung === true)
<div class="mb-4 px-4 py-3 rounded-lg bg-amber-100 text-amber-700 text-sm">
    Akun Anda belum terhubung dengan data pegawai.
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Daftar Tugas Saya</h3>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Judul Tugas</th>
                        <th class="pb-3 text-left">Deskripsi</th>
                        <th class="pb-3 text-left">Tanggal Tugas</th>
                        <th class="pb-3 text-left">Deadline</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-left">Progres</th>
                        <th class="pb-3 text-left">Kondisi</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($tugas as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <td class="py-4 font-medium text-slate-800">
                            {{ $item['judul'] }}
                        </td>

                        <td class="py-4 text-slate-600">
                            {{ $item['deskripsi'] }}
                        </td>
                        <td class="py-4">
                            {{ optional($item->tanggal_tugas)->format('d M Y') ?? '-' }}
                        </td>

                        <td class="py-4">
                            {{ \Carbon\Carbon::parse($item['deadline'])->format('d M Y') }}
                        </td>

                        @php
                        $penugasanSaya = $item->penugasan->firstWhere(
                        'pegawai.user_id',
                        auth()->id()
                        );
                        @endphp

                        <td class="py-4">
                            @if ($penugasanSaya)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                {{ $penugasanSaya->status }}
                            </span>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="py-4">{{ $penugasanSaya->progres_persen ?? 0 }}%</td>
                        <td class="py-4">
                            @if ($penugasanSaya?->is_terlambat)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Terlambat</span>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>

                        <td class="py-4 text-right">
                            <a
                                href="{{ route('pegawai.tugas.show', $item['id']) }}"
                                class="text-slate-600 hover:text-blue-600 font-medium transition">
                                Detail
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            Tugas belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Tugas -->
<div id="detailModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Detail Tugas</h3>
            <button onclick="closeDetailModal()"
                class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5 text-sm overflow-y-auto">

            <!-- Judul -->
            <div>
                <strong>Judul Tugas</strong>
                <p id="detail_judul" class="text-slate-700 mt-1"></p>
            </div>

            <!-- Deskripsi -->
            <div>
                <strong>Deskripsi</strong>
                <p id="detail_deskripsi" class="text-slate-700 mt-1"></p>
            </div>

            <!-- Template -->
            <div>
                <strong>Template Tugas</strong>
                <div id="detail_template" class="mt-2"></div>
            </div>

            <!-- Deadline & Prioritas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <strong>Deadline</strong>
                    <p id="detail_deadline" class="text-slate-700 mt-1"></p>
                </div>

                <div>
                    <strong>Prioritas</strong>
                    <p id="detail_prioritas" class="text-slate-700 mt-1"></p>
                </div>
            </div>

            <!-- Pegawai -->
            <div>
                <strong>Daftar Pegawai</strong>
                <div class="mt-2 border rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-slate-500 text-xs uppercase">
                                <th class="px-3 py-2 text-left">Nama</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="detail_pegawai" class="divide-y"></tbody>
                    </table>
                </div>
            </div>

            <div>
                <strong>Foto Progres</strong>

                <div id="detail_foto_progres"
                    class="mt-3 grid grid-cols-4 gap-2">
                    {{-- Foto progres akan di-render via JS --}}
                </div>
            </div>

            <div>
                <strong>Laporan Akhir</strong>

                <div id="detail_laporan"
                    class="mt-2">
                    {{-- Link laporan akan di-render via JS --}}
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t bg-gray-50 text-right">
            <button onclick="closeDetailModal()"
                class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                Tutup
            </button>
        </div>

    </div>
</div>

<script>
    const tugasData = @json($tugas);
</script>

<script>
    let selectedTugas = null;

    function openDetailModal(id) {
        const tugas = tugasData.find(t => t.id === id);
        if (!tugas) return;

        document.getElementById('detail_judul').textContent = tugas.judul;
        document.getElementById('detail_deskripsi').textContent = tugas.deskripsi;

        const detailLaporan = document.getElementById('detail_laporan');

        const laporan = tugas.penugasan.find(p => p.laporan)?.laporan ?? null;

        detailLaporan.innerHTML = laporan ?
            `
            <a href="/storage/${laporan}" target="_blank"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm
                    text-blue-700 bg-blue-50 rounded-lg
                    hover:bg-blue-100 transition">
                Lihat Laporan
            </a>
        ` :
            `<span class="text-slate-400 text-sm">Belum ada laporan</span>`;


        const templateContainer = document.getElementById('detail_template');

        if (tugas.template) {
            templateContainer.innerHTML = `
            <a href="/storage/${tugas.template}" target="_blank"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm
                       text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                Unduh Template
            </a>
        `;
        } else {
            templateContainer.innerHTML =
                `<span class="text-slate-400 text-sm">Tidak ada template</span>`;
        }

        // ===== FOTO PROGRES =====
        const fotoContainer = document.getElementById('detail_foto_progres');
        fotoContainer.innerHTML = '';

        let semuaFoto = [];

        if (tugas.penugasan && tugas.penugasan.length > 0) {
            tugas.penugasan.forEach(p => {

                let fotos = [];

                // jika masih string JSON
                if (typeof p.foto_progres === 'string') {
                    try {
                        fotos = JSON.parse(p.foto_progres);
                    } catch (e) {
                        fotos = [];
                    }
                }

                // jika sudah array
                if (Array.isArray(p.foto_progres)) {
                    fotos = p.foto_progres;
                }

                fotos.forEach(f => {
                    semuaFoto.push(f);
                });
            });
        }

        if (semuaFoto.length > 0) {
            semuaFoto.forEach(foto => {
                const img = document.createElement('img');
                img.src = `/storage/${foto}`;
                img.className = 'w-full h-20 object-cover rounded-lg border';
                img.loading = 'lazy';

                fotoContainer.appendChild(img);
            });
        } else {
            fotoContainer.innerHTML = `
        <span class="col-span-4 text-sm text-gray-400">
            Belum ada foto progres
        </span>
    `;
        }

        document.getElementById('detail_deadline').textContent =
            new Date(tugas.deadline).toLocaleDateString('id-ID');

        let prioritasBadge = '';
        if (tugas.prioritas === 'rendah') {
            prioritasBadge = `<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Rendah</span>`;
        } else if (tugas.prioritas === 'sedang') {
            prioritasBadge = `<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Sedang</span>`;
        } else {
            prioritasBadge = `<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Tinggi</span>`;
        }
        document.getElementById('detail_prioritas').innerHTML = prioritasBadge;

        const tbody = document.getElementById('detail_pegawai');
        tbody.innerHTML = '';

        if (tugas.penugasan.length > 0) {
            tugas.penugasan.forEach(p => {
                let badge =
                    p.status === 'selesai' ?
                    `<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Selesai</span>` :
                    p.status === 'baru' ?
                    `<span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Baru</span>` :
                    `<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Proses</span>`;

                tbody.innerHTML += `
                <tr>
                    <td class="px-3 py-2">${p.pegawai?.user?.name ?? '-'}</td>
                    <td class="px-3 py-2">${badge}</td>
                    <td class="px-3 py-2">${p.catatan_kepegawaian ?? '-'}</td>
                </tr>
            `;
            });
        } else {
            tbody.innerHTML = `
            <tr>
                <td colspan="3" class="px-3 py-4 text-center text-slate-500">
                    Tidak ada pegawai
                </td>
            </tr>
        `;
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

</script>

@endsection
