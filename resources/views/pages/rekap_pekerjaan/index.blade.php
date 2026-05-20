@extends('layouts.master')

@section('title', $pageTitle)

@section('content')
<div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h1 class="text-2xl font-bold text-slate-800">Rekap Pekerjaan</h1>
        <p class="text-sm text-slate-600 mt-1">Rekap tugas dan catatan kegiatan berdasarkan periode.</p>
        <p class="text-sm text-slate-500 mt-2">Periode aktif: <span class="font-semibold text-slate-700">{{ $periodeLabel }}</span></p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <form method="GET" action="{{ route($routePrefix . '.rekap-pekerjaan.index') }}" class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Periode</label>
                <select name="periode" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="harian" @selected(($filters['periode'] ?? 'harian') === 'harian')>Harian</option>
                    <option value="mingguan" @selected(($filters['periode'] ?? '') === 'mingguan')>Mingguan</option>
                    <option value="bulanan" @selected(($filters['periode'] ?? '') === 'bulanan')>Bulanan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Acuan</label>
                <input type="date" name="tanggal" value="{{ $filters['tanggal'] ?? '' }}" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bulan</label>
                <input type="month" name="bulan" value="{{ $filters['bulan'] ?? '' }}" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pegawai</label>
                <select name="pegawai_id" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Pegawai</option>
                    @foreach($filterOptions['pegawai'] as $pegawai)
                    <option value="{{ $pegawai->id }}" @selected((string) ($filters['pegawai_id'] ?? '') === (string) $pegawai->id)>
                        {{ $pegawai->user->name ?? ('Pegawai #' . $pegawai->id) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Unit Kerja</label>
                <select name="unit_kerja_id" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($filterOptions['unitKerja'] as $unit)
                    <option value="{{ $unit->id }}" @selected((string) ($filters['unit_kerja_id'] ?? '') === (string) $unit->id)>{{ $unit->nama_unitkerja }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status Tugas</label>
                <select name="status" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    @foreach($filterOptions['statusTugas'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Prioritas</label>
                <select name="prioritas" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Prioritas</option>
                    @foreach($filterOptions['prioritas'] as $prioritas)
                    <option value="{{ $prioritas }}" @selected(($filters['prioritas'] ?? '') === $prioritas)>{{ ucfirst($prioritas) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-medium">Terapkan Filter</button>
                <a href="{{ route($routePrefix . '.rekap-pekerjaan.index') }}" class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 text-sm font-medium">Reset</a>
            </div>
        </form>
        <div class="mt-3 flex flex-wrap gap-2">
            <a href="{{ route($routePrefix . '.rekap-pekerjaan.export-pdf', request()->query()) }}" class="px-3 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800 text-xs font-medium">Export PDF Rekap</a>
            <a href="{{ route($routePrefix . '.laporan.tugas.export-pdf', request()->query()) }}" class="px-3 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800 text-xs font-medium">Export PDF Tugas</a>
            <a href="{{ route($routePrefix . '.laporan.catatan.export-pdf', request()->query()) }}" class="px-3 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800 text-xs font-medium">Export PDF Catatan</a>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Total Tugas</p><p class="text-2xl font-bold text-slate-800">{{ $summaryTugas['total_tugas'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Selesai</p><p class="text-2xl font-bold text-emerald-700">{{ $summaryTugas['tugas_selesai'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Belum Selesai</p><p class="text-2xl font-bold text-amber-700">{{ $summaryTugas['tugas_belum_dikerjakan'] + $summaryTugas['tugas_sedang_dikerjakan'] + $summaryTugas['tugas_menunggu_verifikasi'] + $summaryTugas['tugas_revisi'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Menunggu Verifikasi</p><p class="text-2xl font-bold text-blue-700">{{ $summaryTugas['tugas_menunggu_verifikasi'] }}</p></div>
        <div class="p-4 bg-red-50 border border-red-100 rounded-lg"><p class="text-xs text-red-600">Terlambat</p><p class="text-2xl font-bold text-red-700">{{ $summaryTugas['tugas_terlambat'] }}</p></div>
    </div>

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Total Catatan</p><p class="text-2xl font-bold text-slate-800">{{ $summaryCatatan['total_catatan'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Menunggu Verifikasi</p><p class="text-2xl font-bold text-blue-700">{{ $summaryCatatan['catatan_menunggu_verifikasi'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Disetujui</p><p class="text-2xl font-bold text-emerald-700">{{ $summaryCatatan['catatan_disetujui'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Revisi</p><p class="text-2xl font-bold text-amber-700">{{ $summaryCatatan['catatan_revisi'] }}</p></div>
        <div class="p-4 bg-white border rounded-lg"><p class="text-xs text-slate-500">Ditolak</p><p class="text-2xl font-bold text-red-700">{{ $summaryCatatan['catatan_ditolak'] }}</p></div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b bg-slate-50"><h2 class="font-semibold text-slate-800">Rekap Per Pegawai</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="text-left px-4 py-2">Pegawai</th><th class="text-left px-4 py-2">Jabatan</th><th class="text-left px-4 py-2">Unit</th><th class="text-left px-4 py-2">Total</th><th class="text-left px-4 py-2">Selesai</th><th class="text-left px-4 py-2">Belum</th><th class="text-left px-4 py-2">Terlambat</th><th class="text-left px-4 py-2">Catatan</th><th class="text-left px-4 py-2">Disetujui</th><th class="text-left px-4 py-2">Revisi/Menunggu</th><th class="text-left px-4 py-2">% Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapPegawai as $row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $row->nama_pegawai }}</td><td class="px-4 py-2">{{ $row->jabatan ?? '-' }}</td><td class="px-4 py-2">{{ $row->unit_kerja ?? '-' }}</td><td class="px-4 py-2">{{ $row->total_tugas }}</td><td class="px-4 py-2">{{ $row->tugas_selesai }}</td><td class="px-4 py-2">{{ $row->tugas_belum_selesai }}</td><td class="px-4 py-2">{{ $row->tugas_terlambat }}</td><td class="px-4 py-2">{{ $row->total_catatan }}</td><td class="px-4 py-2">{{ $row->catatan_disetujui }}</td><td class="px-4 py-2">{{ $row->catatan_revisi_menunggu }}</td><td class="px-4 py-2">{{ $row->persentase_selesai }}%</td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="px-4 py-6 text-center text-slate-500">Tidak ada data pegawai pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b bg-slate-50"><h2 class="font-semibold text-slate-800">Rekap Per Unit Kerja</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="text-left px-4 py-2">Unit Kerja</th><th class="text-left px-4 py-2">Jumlah Pegawai</th><th class="text-left px-4 py-2">Total Tugas</th><th class="text-left px-4 py-2">Selesai</th><th class="text-left px-4 py-2">Belum</th><th class="text-left px-4 py-2">Terlambat</th><th class="text-left px-4 py-2">Total Catatan</th><th class="text-left px-4 py-2">Catatan Disetujui</th><th class="text-left px-4 py-2">% Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapUnitKerja as $row)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $row->nama_unitkerja }}</td><td class="px-4 py-2">{{ $row->jumlah_pegawai }}</td><td class="px-4 py-2">{{ $row->total_tugas }}</td><td class="px-4 py-2">{{ $row->tugas_selesai }}</td><td class="px-4 py-2">{{ $row->tugas_belum_selesai }}</td><td class="px-4 py-2">{{ $row->tugas_terlambat }}</td><td class="px-4 py-2">{{ $row->total_catatan }}</td><td class="px-4 py-2">{{ $row->catatan_disetujui }}</td><td class="px-4 py-2">{{ $row->persentase_selesai }}%</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-4 py-6 text-center text-slate-500">Tidak ada data unit kerja pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b bg-slate-50"><h2 class="font-semibold text-slate-800">Detail Tugas</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="text-left px-4 py-2">Tanggal</th><th class="text-left px-4 py-2">Judul</th><th class="text-left px-4 py-2">Pegawai</th><th class="text-left px-4 py-2">Unit</th><th class="text-left px-4 py-2">Prioritas</th><th class="text-left px-4 py-2">Deadline</th><th class="text-left px-4 py-2">Status</th><th class="text-left px-4 py-2">Progres</th><th class="text-left px-4 py-2">Terlambat</th><th class="text-left px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarTugas as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ optional($item->tugas->tanggal_tugas)->format('d-m-Y') ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->tugas->judul ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
                        <td class="px-4 py-2">{{ ucfirst($item->tugas->prioritas ?? '-') }}</td>
                        <td class="px-4 py-2">{{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }}</td>
                        <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                        <td class="px-4 py-2">{{ (int) ($item->progres_persen ?? 0) }}%</td>
                        <td class="px-4 py-2">{{ $item->is_terlambat ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-2"><a class="text-blue-700" href="{{ route($routePrefix . '.penugasan.show', $item->tugas_id) }}">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-4 py-6 text-center text-slate-500">Tidak ada tugas pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $daftarTugas->links() }}</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b bg-slate-50"><h2 class="font-semibold text-slate-800">Detail Catatan Kegiatan</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="text-left px-4 py-2">Tanggal</th><th class="text-left px-4 py-2">Pegawai</th><th class="text-left px-4 py-2">Tugas</th><th class="text-left px-4 py-2">Ringkasan Hasil</th><th class="text-left px-4 py-2">Status Verifikasi</th><th class="text-left px-4 py-2">Diverifikasi Oleh</th><th class="text-left px-4 py-2">Diverifikasi Pada</th><th class="text-left px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarCatatan as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->pegawai->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->penugasan->tugas->judul ?? '-' }}</td>
                        <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($item->hasil_kegiatan ?? $item->deskripsi ?? '-', 100) }}</td>
                        <td class="px-4 py-2">{{ $item->status_verifikasi_label }}</td>
                        <td class="px-4 py-2">{{ $item->verifier->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ optional($item->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-2"><a class="text-blue-700" href="{{ route($routePrefix . '.catatan_kegiatan.show', $item->id) }}">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-6 text-center text-slate-500">Tidak ada catatan kegiatan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">{{ $daftarCatatan->links() }}</div>
    </div>
</div>
@endsection
