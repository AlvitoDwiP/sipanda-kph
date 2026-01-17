@extends('layouts.master')

@section('title', 'Data Kepegawaian')
@section('page-title', 'Data Kepegawaian')

@section('content')

@if (session('success'))
<div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-100">

    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Data Kepegawaian</h3>
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
                            <button type="button" class="text-slate-600 hover:text-red-600 font-medium transition" data-pegawai-id="{{$item->id}}" onclick="openDetailModal(this.getAttribute('data-pegawai-id'))">
                                Detail
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

<!-- Modal Detail Pegawai -->
<div id="detailModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-5xl max-h-[95vh] flex flex-col overflow-y-auto">

        <!-- Header -->
        <div class="relative px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 text-center">Detail Pegawai</h3>
            <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" onclick="closeDetailModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">

            <!-- Foto Profil Center -->
            <div class="flex justify-center mb-6">
                <img id="detail_foto" src="" alt="Foto Pegawai" class="w-40 h-40 object-cover rounded-full border-2 border-slate-200" />
            </div>

            <!-- Grid Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Data User -->
                <div class="col-span-2">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">Data User</h4>
                </div>
                <div><strong>Nama:</strong>
                    <p id="detail_nama" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>NIP:</strong>
                    <p id="detail_nip" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Email:</strong>
                    <p id="detail_email" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Role:</strong>
                    <p id="detail_role" class="mt-1 text-slate-800">-</p>
                </div>

                <!-- Data Kepegawaian -->
                <div class="col-span-2 mt-4">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">Data Kepegawaian</h4>
                </div>
                <div><strong>Unit Kerja:</strong>
                    <p id="detail_unitkerja" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Golongan:</strong>
                    <p id="detail_golongan" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Jabatan:</strong>
                    <p id="detail_jabatan" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Status Pegawai:</strong>
                    <p id="detail_status_pegawai" class="mt-1 text-slate-800">-</p>
                </div>

                <!-- Data Diri -->
                <div class="col-span-2 mt-4">
                    <h4 class="font-semibold text-slate-700 mb-3 pb-2 border-b">Data Diri</h4>
                </div>
                <div><strong>No HP:</strong>
                    <p id="detail_no_hp" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Alamat:</strong>
                    <p id="detail_alamat" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Tempat Lahir:</strong>
                    <p id="detail_tempat_lahir" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Tanggal Lahir:</strong>
                    <p id="detail_tgl_lahir" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Jenis Kelamin:</strong>
                    <p id="detail_jenis_kelamin" class="mt-1 text-slate-800">-</p>
                </div>
                <div><strong>Kartu Identitas:</strong>
                    <p id="detail_kartu_identitas" class="mt-1"></p>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition" onclick="closeDetailModal()">
                Tutup
            </button>
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('detailModal');
        const baseUrl = "{{ url('kph/pegawai') }}";
        const storageUrl = "{{ asset('storage') }}";

        window.openDetailModal = function(pegawaiId) {
            if (!modal) return;

            modal.classList.remove('hidden');

            setLoading();

            fetch(`${baseUrl}/${pegawaiId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    return response.json();
                })
                .then(data => {

                    setText('detail_nama', data.user?.name);
                    setText('detail_nip', data.user?.nip);
                    setText('detail_email', data.user?.email);
                    setText('detail_role', data.user?.role);

                    setText('detail_unitkerja', data.unitkerja?.nama_unitkerja);
                    setText('detail_golongan', data.golongan?.nama_golongan);
                    setText('detail_jabatan', data.jabatan?.nama_jabatan);
                    setText('detail_status_pegawai', data.status_pegawai);

                    const diri = data.data_diri ?? {};
                    setText('detail_no_hp', diri.no_hp);
                    setText('detail_alamat', diri.alamat);
                    setText('detail_tempat_lahir', diri.tempat_lahir);
                    setText('detail_tgl_lahir', diri.tgl_lahir ? new Date(diri.tgl_lahir).toLocaleDateString('id-ID') : '-');
                    setText('detail_jenis_kelamin', diri.jenis_kelamin);

                    const fotoEl = document.getElementById('detail_foto');
                    if (diri?.foto) {
                        fotoEl.src = "{{ asset('storage') }}/" + diri.foto;
                        fotoEl.alt = "Foto Pegawai";
                    } else {
                        fotoEl.src = "";
                        fotoEl.alt = "Gambar kosong / bermasalah";
                    }
                                        const kartuWrapper = document.getElementById('detail_kartu_identitas');

                    if (kartuWrapper) {
                        if (diri.kartu_identitas) {
                            kartuWrapper.innerHTML = `
                            <a href="${storageUrl}/${diri.kartu_identitas}"
                               download
                               class="inline-flex items-center gap-2 px-3 py-2 text-sm
                                      bg-green-800 text-white rounded-md hover:bg-green-900 transition">
                                Download Kartu Identitas
                            </a>
                        `;
                        } else {
                            kartuWrapper.textContent = '-';
                        }
                    }

                })
                .catch(err => {
                    console.error(err);
                    setLoading('Gagal memuat data');
                    const fotoEl = document.getElementById('detail_foto');
                    fotoEl.src = "";
                    fotoEl.alt = "Gambar kosong / bermasalah";
                });
        };

        window.closeDetailModal = function() {
            if (!modal) return;
            modal.classList.add('hidden');
        };

        modal?.addEventListener('click', function(e) {
            if (e.target === modal) closeDetailModal();
        });

        function setText(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = value ?? '-';
        }

        function setLoading(text = 'Memuat...') {
            document.querySelectorAll('[id^="detail_"]').forEach(el => {
                if (el.tagName !== 'IMG') el.textContent = text;
            });
        }

        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', function() {
                const pegawaiId = this.getAttribute('data-pegawai-id');
                openDetailModal(pegawaiId);
            });
        });

    });
</script>

@endsection