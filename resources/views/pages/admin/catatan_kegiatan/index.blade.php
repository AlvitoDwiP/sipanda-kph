@extends('layouts.master')

@section('title', 'Catatan Kegiatan Pegawai')
@section('page-title', 'Catatan Kegiatan Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">
            Catatan Kegiatan Pegawai
        </h3>
    </div>

    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Pegawai</th>
                        <th class="pb-3 text-left">Unit Kerja</th>
                        <th class="pb-3 text-left">Periode</th>
                        <th class="pb-3 text-left">Judul</th>
                        <th class="pb-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($catatan as $i => $item)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-4">{{ $i + 1 }}</td>

                        <!-- Pegawai -->
                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->pegawai->user->name ?? '-' }}
                            </div>
                            <div class="text-xs text-slate-500">
                                NIP: {{ $item->pegawai->user->nip ?? '-' }}
                            </div>
                        </td>

                        <!-- Unit Kerja -->
                        <td class="py-4">
                            {{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}
                        </td>

                        <!-- Periode -->
                        <td class="py-4">
                            {{ \Carbon\Carbon::create()->month($item->periode_bulan)->translatedFormat('F') }}
                            {{ $item->periode_tahun }}
                        </td>

                        <!-- Judul -->
                        <td class="py-4">
                            <div class="font-medium text-slate-800">
                                {{ $item->judul }}
                            </div>
                            <div class="text-xs text-slate-500 line-clamp-1">
                                {{ $item->deskripsi }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4">
                            <select
                                class="status-select px-3 py-1 text-xs font-semibold rounded-full border cursor-pointer focus:outline-none"
                                data-id="{{ $item->id }}">

                                <option value="ajukan" @selected($item->status === 'ajukan')>
                                    Diajukan
                                </option>

                                <option value="setuju" @selected($item->status === 'setuju')>
                                    Disetujui
                                </option>

                                <option value="tolak" @selected($item->status === 'tolak')>
                                    Ditolak
                                </option>
                            </select>
                        </td>


                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">
                            Catatan kegiatan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CATATAN ADMIN -->
<div id="modalCatatanAdmin"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg">

        <div class="px-6 py-4 border-b font-semibold">
            Alasan Penolakan
        </div>

        <div class="p-6">
            <textarea id="catatanAdminInput" rows="4"
                class="w-full px-3 py-2 border rounded-lg text-sm"
                placeholder="Isi alasan penolakan (opsional)"></textarea>
        </div>

        <div class="px-6 py-4 border-t flex justify-end gap-3">
            <button onclick="closeCatatanAdmin()"
                class="px-4 py-2 border rounded-lg">
                Batal
            </button>
            <button onclick="submitTolak()"
                class="px-4 py-2 bg-red-600 text-white rounded-lg">
                Simpan
            </button>
        </div>

    </div>
</div>


@endsection

@push('scripts')
<script>
    let selectedCatatanId = null;
    let selectedStatus = null;

    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.value;

            if (status === 'tolak') {
                selectedCatatanId = id;
                selectedStatus = status;
                openCatatanAdmin();
            } else {
                updateStatus(id, status);
            }
        });
    });

    function updateStatus(id, status, catatan = null) {
        fetch(`/admin/catatan-kegiatan/${id}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                catatan_status: catatan
            })
        }).then(() => location.reload());
    }

    function openCatatanAdmin() {
        document.getElementById('modalCatatanAdmin').classList.remove('hidden');
        document.getElementById('modalCatatanAdmin').classList.add('flex');
    }

    function closeCatatanAdmin() {
        document.getElementById('modalCatatanAdmin').classList.add('hidden');
        document.getElementById('modalCatatanAdmin').classList.remove('flex');
        document.getElementById('catatanAdminInput').value = '';
    }

    function submitTolak() {
        const catatan = document.getElementById('catatanAdminInput').value;
        updateStatus(selectedCatatanId, selectedStatus, catatan);
    }
</script>
<script>
    function updateStatusStyle(select) {
        select.classList.remove(
            'bg-green-100', 'text-green-700', 'border-green-300',
            'bg-red-100', 'text-red-700', 'border-red-300',
            'bg-yellow-100', 'text-yellow-700', 'border-yellow-300'
        );

        if (select.value === 'setuju') {
            select.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
        } else if (select.value === 'tolak') {
            select.classList.add('bg-red-100', 'text-red-700', 'border-red-300');
        } else {
            select.classList.add('bg-yellow-100', 'text-yellow-700', 'border-yellow-300');
        }
    }

    document.querySelectorAll('.status-select').forEach(select => {
        updateStatusStyle(select); // initial load
        select.addEventListener('change', () => updateStatusStyle(select));
    });
</script>

@endpush