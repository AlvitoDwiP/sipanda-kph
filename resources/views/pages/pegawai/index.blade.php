@extends('layouts.master')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Pegawai</h2>
    <p class="text-gray-600">Selamat Datang Kembali, {{ Auth::user()->name }}.</p>
</div>

<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-full">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                       M9 5a2 2 0 002 2h2a2 2 0 002-2
                       M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-600">Jumlah Tugas Saya</p>
            <p class="text-lg font-bold text-gray-700">{{ number_format($stats['jumlah_tugas'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-amber-600 bg-amber-100 rounded-full">

            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3
                       a9 9 0 11-18 0
                       a9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-600">Tugas Belum Selesai</p>
            <p class="text-lg font-bold text-gray-700">{{ number_format($stats['tugas_belum_selesai'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex items-center p-4 bg-white rounded-xl shadow-sm border">
        <div class="p-3 mr-4 text-emerald-600 bg-emerald-100 rounded-full">

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
            <p class="text-sm text-gray-600">Status Catatan Bulan Ini</p>
            <p class="text-lg font-bold text-gray-700">{{ number_format($stats['catatan_bulan_ini'], 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">

    <div class="col-span-2 bg-white rounded-xl shadow-sm border p-6">
        <h3 class="mb-4 font-semibold text-gray-700">
            Progress Tugas Bulan Ini
        </h3>

        <div class="flex items-center justify-center">
            <svg width="160" height="160" viewBox="0 0 42 42" class="transform -rotate-90">
                <circle
                    cx="21"
                    cy="21"
                    r="15.9155"
                    fill="transparent"
                    stroke="#E5E7EB"
                    stroke-width="4" />
                <circle
                    cx="21"
                    cy="21"
                    r="15.9155"
                    fill="transparent"
                    stroke="#10B981"
                    stroke-width="4"
                    stroke-dasharray="{{ $progress_data['persentase_selesai'] }} {{ 100 - $progress_data['persentase_selesai'] }}"
                    stroke-linecap="round" />
            </svg>

            <div class="ml-6">
                <p class="text-sm text-gray-500">Tugas Selesai</p>
                <p class="text-3xl font-bold text-gray-800">{{ $progress_data['persentase_selesai'] }}%</p>

                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span>Selesai: {{ $progress_data['jumlah_selesai'] }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span>Pending: {{ $progress_data['jumlah_pending'] }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="font-semibold text-gray-700 mb-2">
            Aksi Cepat
        </h3>
        <p class="text-sm text-gray-500 mb-6">
            Akses cepat ke fitur yang sering digunakan
        </p>

        <div class="flex flex-col gap-3">
            <a href="{{route('pegawai.tugas.index')}}"
                class="w-full flex items-center justify-center gap-2 px-4 py-3
                  text-sm font-medium text-white bg-emerald-600
                  rounded-lg hover:bg-emerald-700 transition">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10
                       a2 2 0 002-2V7a2 2 0 00-2-2h-2
                       M9 5a2 2 0 002 2h2a2 2 0 002-2
                       M9 5a2 2 0 012-2h2a2 2 0 012 2
                       m-6 9l2 2 4-4" />
                </svg>
                Perbarui Status Tugas
            </a>

            <a href="{{ route('pegawai.catatan_kegiatan.create') }}"
                class="w-full flex items-center justify-center gap-2 px-4 py-3
                  text-sm font-medium text-white bg-emerald-600
                  rounded-lg hover:bg-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11
                       a2 2 0 002 2h11
                       a2 2 0 002-2v-5
                       m-1.414-9.414
                       a2 2 0 112.828 2.828
                       L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Buat Catatan Kegiatan
            </a>
        </div>
    </div>


</div>

<div class="bg-white rounded-xl shadow-sm border">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="font-bold">Daftar Tugas Aktif</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr class="text-slate-500 uppercase text-xs tracking-wide">
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">Judul Tugas</th>
                    <th class="px-6 py-4 text-left">Deskripsi</th>
                    <th class="px-6 py-4 text-left">Deadline</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($tugas_aktif as $index => $penugasan)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>

                    <td class="px-6 py-4 font-medium text-slate-800">
                        {{ $penugasan->tugas->judul ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4 text-slate-600">
                        {{ Str::limit($penugasan->tugas->deskripsi ?? 'N/A', 50) }}
                    </td>

                    <td class="px-6 py-4 text-slate-600">
                        {{ $penugasan->tugas->deadline ? \Carbon\Carbon::parse($penugasan->tugas->deadline)->format('d/m/Y') : 'N/A' }}
                    </td>

                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full
                               text-xs font-medium
                               @if($penugasan->status == 'selesai') bg-emerald-100 text-emerald-700
                               @elseif($penugasan->status == 'proses') bg-amber-100 text-amber-700
                               @else bg-gray-100 text-gray-700
                               @endif">
                            {{ ucfirst($penugasan->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="#"
                            class="text-slate-600 hover:text-blue-600 font-medium transition">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        Tidak ada tugas aktif saat ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection