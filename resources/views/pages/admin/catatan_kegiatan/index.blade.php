@extends('layouts.master')

@section('title', 'Catatan Kegiatan Pegawai')
@section('page-title', 'Catatan Kegiatan Pegawai')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <!-- Header -->
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <!-- Judul -->
        <h3 class="font-bold text-slate-800">
            Catatan Kegiatan Pegawai
        </h3>

        <!-- FILTER -->
        <form method="GET"
            class="flex flex-wrap items-center gap-2">

            <!-- FILTER UNIT -->
            <select name="unit"
                class="px-3 py-2 border rounded-lg text-sm">
                <option value="">Semua Unit Kerja</option>
                @foreach ($unitkerja as $unit)
                <option value="{{ $unit->id }}"
                    @selected(request('unit')==$unit->id)>
                    {{ $unit->nama_unitkerja }}
                </option>
                @endforeach
            </select>

            <!-- TAMPILKAN -->
            <button type="submit"
                class="px-4 py-2 bg-green-800 text-white rounded-lg text-sm">
                Tampilkan
            </button>

            <!-- RESET -->
            @if(request()->has('unit') || request()->has('q'))
            <a href="{{ route('admin.catatan_kegiatan.index') }}"
                class="px-4 py-2 border rounded-lg text-sm text-slate-600 hover:bg-slate-50">
                Reset
            </a>
            @endif

        </form>
    </div>


    <!-- Table -->
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="pb-3 text-left">No</th>
                        <th class="pb-3 text-left">Pegawai</th>
                        <th class="pb-3 text-left">Judul</th>
                        <th class="pb-3 text-left">Status</th>
                        <th class="pb-3 text-left">Aksi</th>
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

                        <td class="py-4">
                            <button type="button"
                                onclick="openDetailModal({{ $item->id }})"
                                class="text-green-800 hover:text-green-900 font-medium transition">
                                Detail
                            </button>

                            @if ($item->status === 'setuju')
                            <span class="mx-2 text-slate-300">|</span>

                            <a href="{{ route('admin.catatan_kegiatan.pdf', $item->id) }}"
                                class="text-green-700 hover:text-green-900 font-medium transition">
                                Download PDF
                            </a>
                            @endif
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

<!-- MODAL DETAIL CATATAN -->
<div id="modalDetail"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white w-full max-w-3xl max-h-[90vh] rounded-xl shadow-lg flex flex-col">

        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">
                Detail Catatan Kegiatan
            </h3>
            <button onclick="closeDetailModal()">✕</button>
        </div>

        <!-- BODY (SCROLLABLE) -->
        <div class="p-6 space-y-4 overflow-y-auto">

            <!-- DATA PEGAWAI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Nama Pegawai</p>
                    <p id="d_nama" class="font-medium"></p>
                </div>
                <div>
                    <p class="text-slate-500">NIP</p>
                    <p id="d_nip" class="font-medium"></p>
                </div>
                <div>
                    <p class="text-slate-500">Jabatan</p>
                    <p id="d_jabatan" class="font-medium"></p>
                </div>
                <div>
                    <p class="text-slate-500">Golongan</p>
                    <p id="d_golongan" class="font-medium"></p>
                </div>
                <div>
                    <p class="text-slate-500">Unit Kerja</p>
                    <p id="d_unit" class="font-medium"></p>
                </div>
                <div>
                    <p class="text-slate-500">Periode</p>
                    <p id="d_periode" class="font-medium"></p>
                </div>
            </div>

            <hr>

            <!-- CATATAN -->
            <div>
                <p class="text-slate-500 text-sm">Judul</p>
                <p id="d_judul" class="font-semibold"></p>
            </div>

            <div>
                <p class="text-slate-500 text-sm">Detail Kegiatan</p>
                <p id="d_deskripsi" class="text-sm whitespace-pre-line"></p>
            </div>

            <!-- FOTO -->
            <div>
                <p class="text-slate-500 text-sm mb-2">Foto Kegiatan</p>
                <div id="d_foto_container"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="px-6 py-4 border-t text-right">
            <button onclick="closeDetailModal()"
                class="px-4 py-2 border rounded-lg text-sm">
                Tutup
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

<script>
    const catatanData = @json($catatan);

    function bulanIndo(bulan) {
        const namaBulan = [
            'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        ];
        return namaBulan[bulan - 1] ?? '-';
    }

    function openDetailModal(id) {
        const item = catatanData.find(c => c.id === id);
        if (!item) return;

        document.getElementById('d_nama').innerText = item.pegawai?.user?.name ?? '-';
        document.getElementById('d_nip').innerText = item.pegawai?.user?.nip ?? '-';
        document.getElementById('d_jabatan').innerText = item.pegawai?.jabatan?.nama_jabatan ?? '-';
        document.getElementById('d_golongan').innerText =
            item.pegawai?.golongan?.nama_golongan ??
            item.pegawai?.golongan ??
            '-';
        document.getElementById('d_unit').innerText = item.pegawai?.unitkerja?.nama_unitkerja ?? '-';

        document.getElementById('d_periode').innerText =
            `${bulanIndo(item.periode_bulan)} ${item.periode_tahun}`;

        document.getElementById('d_judul').innerText = item.judul;
        document.getElementById('d_deskripsi').innerText = item.deskripsi;

        const fotoContainer = document.getElementById('d_foto_container');
        fotoContainer.innerHTML = '';

        if (item.foto_kegiatan) {
            let fotos = item.foto_kegiatan;

            // jika JSON string
            if (typeof fotos === 'string') {
                try {
                    fotos = JSON.parse(fotos);
                } catch {
                    fotos = [fotos];
                }
            }

            fotos.forEach(foto => {
                const img = document.createElement('img');
                img.src = foto.startsWith('storage') ?
                    `/${foto}` :
                    `/storage/${foto}`;
                img.className =
                    'w-full max-h-80 object-contain rounded-lg border bg-slate-50';
                fotoContainer.appendChild(img);
            });
        }

        document.getElementById('modalDetail').classList.remove('hidden');
        document.getElementById('modalDetail').classList.add('flex');
    }

    function closeDetailModal() {
        document.getElementById('modalDetail').classList.add('hidden');
        document.getElementById('modalDetail').classList.remove('flex');
    }
</script>

@endpush