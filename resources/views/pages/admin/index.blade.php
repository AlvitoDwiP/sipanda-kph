@extends('layouts.master')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

{{-- Header --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
    <p class="text-gray-600">Ringkasan sistem manajemen kepegawaian</p>
</div>

{{-- Statistik --}}
<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

    {{-- Pegawai Aktif --}}
    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-green-600 bg-green-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pegawai Aktif</p>
            <p class="text-xl font-bold text-gray-800">
                {{ $jumlahPegawai }}
            </p>
        </div>
    </div>

    {{-- Akun Menunggu Verifikasi --}}
    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3
                       a9 9 0 11-18 0
                       a9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Akun Menunggu Verifikasi</p>
            <p class="text-xl font-bold text-gray-800">
                {{ $akunMenungguVerifikasi }}
            </p>
        </div>
    </div>

    {{-- Tugas Sedang Berjalan --}}
    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-yellow-600 bg-yellow-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                       M9 5a2 2 0 002 2h2a2 2 0 002-2
                       M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Tugas Sedang Berjalan</p>
            <p class="text-xl font-bold text-gray-800">
                {{ $tugasBerjalan }}
            </p>
        </div>
    </div>

    {{-- Catatan Menunggu Validasi --}}
    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-purple-600 bg-purple-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7
                       a2 2 0 01-2-2V5
                       a2 2 0 012-2h5.586
                       a1 1 0 01.707.293
                       l5.414 5.414
                       a1 1 0 01.293.707V19
                       a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Catatan Menunggu Validasi</p>
            <p class="text-xl font-bold text-gray-800">
                {{ $catatanMenungguValidasi }}
            </p>
        </div>
    </div>

</div>

{{-- Grafik & Aksi --}}
<div class="grid gap-6 mb-8 md:grid-cols-2">

    {{-- Grafik --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Status Tugas</h3>

        <div class="flex items-center justify-center h-48">
            <canvas id="grafikStatusTugas"></canvas>
        </div>

        <div class="flex justify-center gap-6 text-sm mt-4">
            <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                Baru ({{ $grafikTugas['baru'] ?? 0 }})
            </span>
            <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                Proses ({{ $grafikTugas['proses'] ?? 0 }})
            </span>
            <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                Selesai ({{ $grafikTugas['selesai'] ?? 0 }})
            </span>
        </div>
    </div>


    {{-- Aksi Cepat --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-bold text-gray-800 mb-4">Aksi Cepat</h3>

        <div class="space-y-3">
            <a href="{{ route('admin.register.index') }}"
                class="block w-full px-4 py-3 rounded-lg bg-green-500 text-white text-sm font-medium hover:bg-green-600">
                Verifikasi Akun
            </a>
            <a href="{{ route('admin.penugasan.create') }}"
                class="block w-full px-4 py-3 rounded-lg bg-blue-500 text-white text-sm font-medium hover:bg-blue-600">
                Buat Tugas
            </a>
            <a href="{{ route('admin.catatan_kegiatan.index') }}"
                class="block w-full px-4 py-3 rounded-lg bg-yellow-500 text-white text-sm font-medium hover:bg-yellow-600">
                Validasi Catatan
            </a>
            <a href="{{ route('admin.pegawai.index') }}"
                class="block w-full px-4 py-3 rounded-lg bg-purple-500 text-white text-sm font-medium hover:bg-purple-600">
                Kelola Data Kepegawaian
            </a>
        </div>
    </div>

</div>

{{-- Aktivitas Terbaru --}}
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b">
        <h3 class="font-bold text-gray-800">Aktivitas Terbaru</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Waktu</th>
                    <th class="px-6 py-3 text-left">Aktivitas</th>
                    <th class="px-6 py-3 text-left">Pengguna</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($aktivitas as $item)
                    <tr>
                        <td class="px-6 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item['waktu'])->format('H:i') }} WIB
                        </td>
                        <td class="px-6 py-3">
                            {{ $item['aktivitas'] }}
                        </td>
                        <td class="px-6 py-3">
                            {{ $item['pengguna'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                            Tidak ada aktivitas terbaru
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('grafikStatusTugas');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Baru', 'Proses', 'Selesai'],
            datasets: [{
                data: [
                    {{ $grafikTugas['baru'] ?? 0 }},
                    {{ $grafikTugas['proses'] ?? 0 }},
                    {{ $grafikTugas['selesai'] ?? 0 }}
                ],
                backgroundColor: [
                    '#9ca3af', // abu-abu
                    '#facc15', // kuning
                    '#22c55e'  // hijau
                ],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

@endpush