@extends('layouts.master')

@section('title', 'Dashboard Monitoring')
@section('page-title', 'Dashboard Monitoring Pekerjaan')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $dashboardTitle }}</h2>
        <p class="text-sm text-slate-500">Fokus tindak lanjut pekerjaan harian pada {{ \Carbon\Carbon::parse($tanggalFilter)->format('d-m-Y') }}.</p>
    </div>
    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input type="date" name="tanggal" value="{{ $tanggalFilter }}" class="border rounded px-3 py-2 text-sm">
        <select name="unit_kerja_id" class="border rounded px-3 py-2 text-sm">
            <option value="">Semua Unit Kerja</option>
            @foreach($unitKerjaList as $unit)
            <option value="{{ $unit->id }}" @selected((string)$unitKerjaId === (string)$unit->id)>{{ $unit->nama_unitkerja }}</option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-green-800 text-white rounded text-sm">Terapkan</button>
    </form>
</div>

<div class="grid gap-3 mb-6 md:grid-cols-4 xl:grid-cols-7">
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Total</p><p class="text-xl font-bold">{{ $summaryTugas['total_tugas'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Belum</p><p class="text-xl font-bold">{{ $summaryTugas['tugas_belum_dikerjakan'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Dikerjakan</p><p class="text-xl font-bold">{{ $summaryTugas['tugas_sedang_dikerjakan'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Menunggu Verifikasi</p><p class="text-xl font-bold text-blue-700">{{ $summaryTugas['tugas_menunggu_verifikasi'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Revisi</p><p class="text-xl font-bold text-amber-700">{{ $summaryTugas['tugas_revisi'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Selesai</p><p class="text-xl font-bold text-green-700">{{ $summaryTugas['tugas_selesai'] }}</p></div>
    <div class="p-4 bg-red-50 border border-red-100 rounded-lg"><p class="text-xs text-red-600">Terlambat</p><p class="text-xl font-bold text-red-700">{{ $summaryTugas['tugas_terlambat'] }}</p></div>
</div>

<div class="grid gap-3 mb-6 md:grid-cols-4">
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Catatan Menunggu Verifikasi</p><p class="text-xl font-bold text-blue-700">{{ $summaryCatatan['catatan_menunggu_verifikasi'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Catatan Disetujui Hari Ini</p><p class="text-xl font-bold text-green-700">{{ $summaryCatatan['catatan_disetujui_hari_ini'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Catatan Revisi</p><p class="text-xl font-bold text-amber-700">{{ $summaryCatatan['catatan_revisi'] }}</p></div>
    <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Catatan Ditolak</p><p class="text-xl font-bold text-red-700">{{ $summaryCatatan['catatan_ditolak'] }}</p></div>
</div>

<div class="grid gap-6 lg:grid-cols-2 mb-6">
    <div class="bg-white border rounded-xl">
        <div class="p-4 border-b font-semibold">Tugas Mendesak (Prioritas Tinggi)</div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-xs text-slate-500"><th class="text-left pb-2">Tugas</th><th class="text-left pb-2">Pegawai</th><th class="text-left pb-2">Deadline</th><th class="text-left pb-2">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($tugasMendesak as $item)
                    <tr>
                        <td class="py-2">{{ $item->tugas->judul ?? '-' }}<div class="text-xs text-slate-500">{{ $item->status }} | {{ $item->progres_persen ?? 0 }}%</div></td>
                        <td class="py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="py-2">{{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }}</td>
                        <td class="py-2"><a class="text-blue-700" href="{{ route($routePrefix . '.penugasan.show', $item->tugas_id) }}">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-slate-400">Tidak ada tugas prioritas tinggi hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border rounded-xl">
        <div class="p-4 border-b font-semibold text-red-700">Tugas Terlambat</div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-xs text-slate-500"><th class="text-left pb-2">Tugas</th><th class="text-left pb-2">Pegawai</th><th class="text-left pb-2">Unit</th><th class="text-left pb-2">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($tugasTerlambat as $item)
                    <tr>
                        <td class="py-2">{{ $item->tugas->judul ?? '-' }}<div class="text-xs text-slate-500">Deadline: {{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }} | {{ $item->status }}</div></td>
                        <td class="py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="py-2">{{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="py-2"><a class="text-blue-700" href="{{ route($routePrefix . '.penugasan.show', $item->tugas_id) }}">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-slate-400">Tidak ada tugas terlambat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2 mb-6">
    <div class="bg-white border rounded-xl">
        <div class="p-4 border-b font-semibold">Catatan Menunggu Verifikasi</div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-xs text-slate-500"><th class="text-left pb-2">Pegawai</th><th class="text-left pb-2">Tugas</th><th class="text-left pb-2">Tanggal</th><th class="text-left pb-2">Aksi</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($catatanMenungguVerifikasi as $item)
                    <tr>
                        <td class="py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="py-2">{{ $item->penugasan->tugas->judul ?? '-' }}</td>
                        <td class="py-2">{{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? $item->created_at?->format('d-m-Y H:i') }}</td>
                        <td class="py-2"><a class="text-blue-700" href="{{ route($routePrefix . '.catatan_kegiatan.show', $item->id) }}">Verifikasi</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-slate-400">Tidak ada catatan menunggu verifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border rounded-xl">
        <div class="p-4 border-b font-semibold">Pegawai Belum Update Progres</div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-xs text-slate-500"><th class="text-left pb-2">Pegawai</th><th class="text-left pb-2">Unit</th><th class="text-left pb-2">Tugas Hari Ini</th><th class="text-left pb-2">Belum Update</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($pegawaiBelumUpdate as $row)
                    <tr>
                        <td class="py-2">{{ $row['pegawai']->user->name ?? '-' }}</td>
                        <td class="py-2">{{ $row['pegawai']->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="py-2">{{ $row['jumlah_tugas_hari_ini'] }}</td>
                        <td class="py-2 text-amber-700 font-semibold">{{ $row['jumlah_belum_update'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-slate-400">Semua pegawai sudah memperbarui progres.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bg-white border rounded-xl">
    <div class="p-4 border-b font-semibold">Ringkasan Progres Per Unit Kerja</div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-xs text-slate-500"><th class="text-left pb-2">Unit Kerja</th><th class="text-left pb-2">Total</th><th class="text-left pb-2">Selesai</th><th class="text-left pb-2">Belum Selesai</th><th class="text-left pb-2">Terlambat</th><th class="text-left pb-2">% Selesai</th></tr></thead>
            <tbody class="divide-y">
                @forelse($summaryUnitKerja as $unit)
                <tr>
                    <td class="py-2">{{ $unit['nama_unitkerja'] }}</td>
                    <td class="py-2">{{ $unit['total_tugas'] }}</td>
                    <td class="py-2 text-green-700">{{ $unit['selesai'] }}</td>
                    <td class="py-2">{{ $unit['belum_selesai'] }}</td>
                    <td class="py-2 text-red-700">{{ $unit['terlambat'] }}</td>
                    <td class="py-2">{{ $unit['persentase_selesai'] }}%</td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-4 text-slate-400">Belum ada data unit kerja untuk filter tanggal ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
