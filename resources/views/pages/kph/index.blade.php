@extends('layouts.master')

@section('title', 'Dashboard Kepala')
@section('page-title', 'Dashboard')

@section('content')

{{-- ================= RINGKASAN ================= --}}
<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

    {{-- Pegawai Aktif --}}
    <div class="flex items-center p-5 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-emerald-600 bg-emerald-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Pegawai Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalPegawaiAktif }}</p>
        </div>
    </div>

    {{-- Tugas Aktif --}}
    <div class="flex items-center p-5 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Tugas Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalTugasAktif }}</p>
        </div>
    </div>

    {{-- Tugas Terlambat --}}
    <div class="flex items-center p-5 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-red-600 bg-red-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3
                       a9 9 0 11-18 0
                       a9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Tugas Terlambat</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalTugasTerlambat }}</p>
        </div>
    </div>

    {{-- Unit Terbanyak --}}
    <div class="flex items-center p-5 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-yellow-600 bg-yellow-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3v18h18"/>
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Unit dengan Tugas Terbanyak</p>
            <p class="text-xl font-bold text-gray-800">{{ $unitTerbanyak }}</p>
        </div>
    </div>
</div>

{{-- ================= GRAFIK ================= --}}
<div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold text-gray-800">Status Tugas Keseluruhan</h3>

        <select class="border rounded-lg text-sm px-3 py-2">
            <option>Semua Unit</option>
        </select>
    </div>

    <div class="flex justify-center items-center h-64">
        <canvas id="statusDonut" class="max-w-xs"></canvas>
    </div>

    <div class="flex justify-center gap-8 text-sm mt-6">
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
            Baru {{ $statusBaru }}
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
            Proses {{ $statusProses }}
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
            Selesai {{ $statusSelesai }}
        </span>
    </div>
</div>

{{-- ================= MONITORING UNIT ================= --}}
<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b">
        <h3 class="font-bold text-gray-800">Monitoring per Unit Kerja</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-3 text-left">Unit Kerja</th>
                    <th class="px-6 py-3 text-center">Jumlah Tugas</th>
                    <th class="px-6 py-3 text-center">Tugas Selesai</th>
                    <th class="px-6 py-3 text-center">Tugas Terlambat</th>
                    <th class="px-6 py-3 text-left">Tingkat Penyelesaian</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($monitoringUnit as $unit)
                <tr>
                    <td class="px-6 py-3">{{ $unit->nama_unitkerja }}</td>
                    <td class="px-6 py-3 text-center">{{ $unit->total }}</td>
                    <td class="px-6 py-3 text-center text-green-600">
                        {{ $unit->selesai }} ({{ $unit->persen }}%)
                    </td>
                    <td class="px-6 py-3 text-center text-red-500">
                        {{ $unit->terlambat }}
                    </td>
                    <td class="px-6 py-3">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                 style="width: {{ $unit->persen }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <a href="#" class="text-green-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('statusDonut');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Baru', 'Proses', 'Selesai'],
        datasets: [{
            data: [
                {{ $statusBaru }},
                {{ $statusProses }},
                {{ $statusSelesai }}
            ],
            backgroundColor: [
                '#9CA3AF', // gray
                '#FACC15', // yellow
                '#22C55E'  // green
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ctx.label + ': ' + ctx.raw;
                    }
                }
            }
        }
    }
});
</script>
@endpush
