@extends('layouts.master')

@section('title', 'Tugas Saya')
@section('page-title', 'Tugas Saya')

@section('content')

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
                        <th class="pb-3 text-left">Deadline</th>
                        <th class="pb-3 text-left">Status</th>
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
                            {{ \Carbon\Carbon::parse($item['deadline'])->format('d M Y') }}
                        </td>

                        @php
                        $penugasanSaya = $item->penugasan->firstWhere(
                        'pegawai.user_id',
                        auth()->id()
                        );

                        $statusClass = match ($penugasanSaya?->status) {
                        'baru' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'proses' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'selesai' => 'bg-green-100 text-green-700 border-green-200',
                        };
                        @endphp

                        <td class="py-4">
                            @if ($penugasanSaya)
                            <select
                                class="status-select px-3 py-1 text-xs font-semibold rounded-full cursor-pointer border {{ $statusClass }}"
                                data-id="{{ $penugasanSaya->id }}"
                                data-current="{{ $penugasanSaya->status }}">

                                <option value="baru" @selected($penugasanSaya->status === 'baru')>
                                    Baru
                                </option>
                                <option value="proses" @selected($penugasanSaya->status === 'proses')>
                                    Proses
                                </option>
                                <option value="selesai" @selected($penugasanSaya->status === 'selesai')>
                                    Selesai
                                </option>
                            </select>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        
                        <td class="py-4 text-right">
                            <button
                                onclick="openDetailModal({{ $item['id'] }})"
                                class="text-slate-600 hover:text-blue-600 font-medium transition">
                                Detail
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
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
<div id="detailModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl">

        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Detail Tugas</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4 text-sm">

            <div>
                <strong>Judul Tugas</strong>
                <p id="detail_judul" class="text-slate-700 mt-1"></p>
            </div>

            <div>
                <strong>Deskripsi</strong>
                <p id="detail_deskripsi" class="text-slate-700 mt-1"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <strong>Deadline</strong>
                    <p id="detail_deadline" class="text-slate-700 mt-1"></p>
                </div>

                <div>
                    <strong>Prioritas Tugas</strong>
                    <p id="detail_prioritas" class="text-slate-700 mt-1"></p>
                </div>
            </div>

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

        </div>


        <!-- Footer -->
        <div class="px-6 py-4 border-t bg-gray-50 text-right">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                Tutup
            </button>
        </div>

    </div>
</div>

<div id="modalCatatan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
        <div class="px-6 py-4 border-b font-semibold">
            Catatan Kepegawaian (Opsional)
        </div>

        <div class="p-6">
            <textarea id="catatanInput" rows="4"
                class="w-full px-3 py-2 border rounded-lg text-sm"
                placeholder="Isi catatan (boleh dikosongkan)"></textarea>
        </div>

        <div class="px-6 py-4 border-t flex justify-end gap-3">
            <button onclick="closeCatatanModal()"
                class="px-4 py-2 border rounded-lg">
                Batal
            </button>
            <button onclick="submitSelesai()"
                class="px-4 py-2 bg-green-600 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
    const tugasData = @json($tugas);
</script>

<script>
    function openDetailModal(id) {
        const tugas = tugasData.find(t => t.id === id);
        if (!tugas) return;

        document.getElementById('detail_judul').textContent = tugas.judul;
        document.getElementById('detail_deskripsi').textContent = tugas.deskripsi;
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
                let badge = '';

                if (p.status === 'selesai') {
                    badge = `<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Selesai</span>`;
                } else if (p.status === 'baru') {
                    badge = `<span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Baru</span>`;
                } else {
                    badge = `<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Proses</span>`;
                }

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

<script>
    let selectedPenugasanId = null;
    let selectedStatus = null;

    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const penugasanId = this.dataset.id;
            const status = this.value;

            if (status === 'selesai') {
                selectedPenugasanId = penugasanId;
                selectedStatus = status;
                openCatatanModal();
            } else {
                updateStatus(penugasanId, status);
            }
        });
    });

    function updateStatus(id, status, catatan = null) {
        fetch(`/pegawai/tugas-saya/penugasan/${id}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                catatan_kepegawaian: catatan
            })
        }).then(() => location.reload());
    }

    function openCatatanModal() {
        document.getElementById('modalCatatan').classList.remove('hidden');
        document.getElementById('modalCatatan').classList.add('flex');
    }

    function closeCatatanModal() {
        document.getElementById('modalCatatan').classList.add('hidden');
        document.getElementById('modalCatatan').classList.remove('flex');
        document.getElementById('catatanInput').value = '';
    }

    function submitSelesai() {
        const catatan = document.getElementById('catatanInput').value;
        updateStatus(selectedPenugasanId, selectedStatus, catatan);
    }
</script>


@endsection