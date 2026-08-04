@extends('layouts.master')

@section('title', $pageTitle)

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-ui.page-header :title="$pageTitle" subtitle="Rekap tugas dan catatan kegiatan berdasarkan periode aktif.">
        <x-slot name="breadcrumbs">
            <x-ui.breadcrumb />
        </x-slot>
        <x-slot name="actions">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-3 py-1 rounded-ui-md text-xs font-bold bg-ui-success-soft text-ui-success border border-ui-success/15 mr-2">
                    Periode: {{ $periodeLabel }}
                </span>
                <x-ui.button variant="secondary" size="sm" leadingIcon="file-text" :href="route($routePrefix . '.rekap-pekerjaan.export-pdf', request()->query())">
                    Ekspor PDF Rekap
                </x-ui.button>
                <x-ui.button variant="secondary" size="sm" leadingIcon="clipboard-list" :href="route($routePrefix . '.laporan.tugas.export-pdf', request()->query())">
                    Ekspor PDF Tugas
                </x-ui.button>
                <x-ui.button variant="secondary" size="sm" leadingIcon="file-edit" :href="route($routePrefix . '.laporan.catatan.export-pdf', request()->query())">
                    Ekspor PDF Catatan
                </x-ui.button>
            </div>
        </x-slot>
    </x-ui.page-header>

    <!-- FILTER SECTION -->
    <x-ui.card title="Filter Laporan Rekapitulasi" icon="filter" variant="default">
        <form method="GET" action="{{ route($routePrefix . '.rekap-pekerjaan.index') }}" class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <div>
                <x-ui.input 
                    type="select" 
                    name="periode" 
                    label="Periode Laporan" 
                    value="{{ $filters['periode'] ?? 'harian' }}"
                >
                    <option value="harian" @selected(($filters['periode'] ?? 'harian') === 'harian')>Harian</option>
                    <option value="mingguan" @selected(($filters['periode'] ?? '') === 'mingguan')>Mingguan</option>
                    <option value="bulanan" @selected(($filters['periode'] ?? '') === 'bulanan')>Bulanan</option>
                </x-ui.input>
            </div>

            <div>
                <x-ui.input 
                    type="date" 
                    name="tanggal" 
                    label="Tanggal Acuan" 
                    value="{{ $filters['tanggal'] ?? '' }}"
                />
            </div>

            <div>
                <x-ui.input 
                    type="month" 
                    name="bulan" 
                    label="Bulan Acuan" 
                    value="{{ $filters['bulan'] ?? '' }}"
                />
            </div>

            <div>
                <x-ui.input 
                    type="select" 
                    name="pegawai_id" 
                    label="Pegawai" 
                    value="{{ $filters['pegawai_id'] ?? '' }}"
                >
                    <option value="">Semua Pegawai</option>
                    @foreach($filterOptions['pegawai'] as $pegawai)
                        <option value="{{ $pegawai->id }}" @selected((string) ($filters['pegawai_id'] ?? '') === (string) $pegawai->id)>
                            {{ $pegawai->user->name ?? ('Pegawai #' . $pegawai->id) }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <div>
                <x-ui.input 
                    type="select" 
                    name="unit_kerja_id" 
                    label="Unit Kerja" 
                    value="{{ $filters['unit_kerja_id'] ?? '' }}"
                >
                    <option value="">Semua Unit Kerja</option>
                    @foreach($filterOptions['unitKerja'] as $unit)
                        <option value="{{ $unit->id }}" @selected((string) ($filters['unit_kerja_id'] ?? '') === (string) $unit->id)>
                            {{ $unit->nama_unitkerja }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <div>
                <x-ui.input 
                    type="select" 
                    name="status" 
                    label="Status Tugas" 
                    value="{{ $filters['status'] ?? '' }}"
                >
                    <option value="">Semua Status</option>
                    @foreach($filterOptions['statusTugas'] as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <div>
                <x-ui.input 
                    type="select" 
                    name="prioritas" 
                    label="Prioritas Tugas" 
                    value="{{ $filters['prioritas'] ?? '' }}"
                >
                    <option value="">Semua Prioritas</option>
                    @foreach($filterOptions['prioritas'] as $prioritas)
                        <option value="{{ $prioritas }}" @selected(($filters['prioritas'] ?? '') === $prioritas)>
                            {{ ucfirst($prioritas) }}
                        </option>
                    @endforeach
                </x-ui.input>
            </div>

            <div class="flex items-end gap-2">
                <x-ui.button type="submit" variant="primary" size="md" leadingIcon="search" class="w-full">
                    Cari
                </x-ui.button>
                <x-ui.button variant="outline" size="md" :href="route($routePrefix . '.rekap-pekerjaan.index')" class="px-5">
                    Reset
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <!-- STATISTICS CARDS -->
    <div class="grid gap-6">
        <div>
            <x-ui.section-header title="Ringkasan Kinerja Tugas" subtitle="Statistik penugasan pegawai pada rentang waktu terpilih." />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-3">
                <x-ui.card variant="statistics" title="Total Tugas" value="{{ $summaryTugas['total_tugas'] }}" icon="clipboard-list" />
                <x-ui.card variant="statistics" title="Selesai" value="{{ $summaryTugas['tugas_selesai'] }}" icon="check-circle" trend="Tercapai" trendType="up" />
                <x-ui.card variant="statistics" title="Belum Selesai" value="{{ $summaryTugas['tugas_belum_dikerjakan'] + $summaryTugas['tugas_sedang_dikerjakan'] + $summaryTugas['tugas_menunggu_verifikasi'] + $summaryTugas['tugas_revisi'] }}" icon="clock" />
                <x-ui.card variant="statistics" title="Menunggu Verifikasi" value="{{ $summaryTugas['tugas_menunggu_verifikasi'] }}" icon="alert-circle" />
                <x-ui.card variant="statistics" title="Terlambat" value="{{ $summaryTugas['tugas_terlambat'] }}" icon="alert-triangle" trend="Perlu Tindakan" trendType="down" />
            </div>
        </div>

        <div>
            <x-ui.section-header title="Ringkasan Catatan Kegiatan" subtitle="Statistik rincian aktivitas dan laporan pekerjaan harian." />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-3">
                <x-ui.card variant="statistics" title="Total Catatan" value="{{ $summaryCatatan['total_catatan'] }}" icon="file-text" />
                <x-ui.card variant="statistics" title="Menunggu Verifikasi" value="{{ $summaryCatatan['catatan_menunggu_verifikasi'] }}" icon="clock" />
                <x-ui.card variant="statistics" title="Disetujui" value="{{ $summaryCatatan['catatan_disetujui'] }}" icon="check-circle" trend="Disetujui" trendType="up" />
                <x-ui.card variant="statistics" title="Revisi" value="{{ $summaryCatatan['catatan_revisi'] }}" icon="edit-3" />
                <x-ui.card variant="statistics" title="Ditolak" value="{{ $summaryCatatan['catatan_ditolak'] }}" icon="x-circle" trend="Ditolak" trendType="down" />
            </div>
        </div>
    </div>

    <!-- TABLE REKAP PER PEGAWAI -->
    <x-ui.card title="Rekap Per Pegawai" icon="users" class="overflow-hidden">
        <x-ui.table 
            :headers="['Pegawai', 'Jabatan', 'Unit Kerja', 'Total Tugas', 'Selesai', 'Belum', 'Terlambat', 'Total Catatan', 'Disetujui', 'Revisi/Menunggu', '% Selesai']"
            :empty="count($rekapPegawai) === 0"
        >
            @foreach($rekapPegawai as $row)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $row->nama_pegawai }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ $row->jabatan ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ $row->unit_kerja ?? '-' }}</td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="neutral" styleType="soft">{{ $row->total_tugas }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="success" styleType="soft">{{ $row->tugas_selesai }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="warning" styleType="soft">{{ $row->tugas_belum_selesai }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge :variant="$row->tugas_terlambat > 0 ? 'danger' : 'neutral'" styleType="soft">
                            {{ $row->tugas_terlambat }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="neutral" styleType="soft">{{ $row->total_catatan }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="success" styleType="soft">{{ $row->catatan_disetujui }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="warning" styleType="soft">{{ $row->catatan_revisi_menunggu }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-right font-bold text-ui-primary">{{ $row->persentase_selesai }}%</td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- TABLE REKAP PER UNIT KERJA -->
    <x-ui.card title="Rekap Per Unit Kerja" icon="building" class="overflow-hidden">
        <x-ui.table 
            :headers="['Unit Kerja', 'Jumlah Pegawai', 'Total Tugas', 'Selesai', 'Belum', 'Terlambat', 'Total Catatan', 'Catatan Disetujui', '% Selesai']"
            :empty="count($rekapUnitKerja) === 0"
        >
            @foreach($rekapUnitKerja as $row)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $row->nama_unitkerja }}</td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="neutral" styleType="soft">{{ $row->jumlah_pegawai }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="neutral" styleType="soft">{{ $row->total_tugas }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="success" styleType="soft">{{ $row->tugas_selesai }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="warning" styleType="soft">{{ $row->tugas_belum_selesai }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge :variant="$row->tugas_terlambat > 0 ? 'danger' : 'neutral'" styleType="soft">
                            {{ $row->tugas_terlambat }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="neutral" styleType="soft">{{ $row->total_catatan }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-center"><x-ui.badge variant="success" styleType="soft">{{ $row->catatan_disetujui }}</x-ui.badge></td>
                    <td class="px-4 py-3 text-right font-bold text-ui-primary">{{ $row->persentase_selesai }}%</td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- DETAIL TUGAS -->
    <x-ui.card title="Detail Tugas" icon="clipboard-list" class="overflow-hidden">
        @php
            $statusVariants = [
                'belum_mulai' => 'neutral',
                'proses' => 'info',
                'menunggu_verifikasi' => 'warning',
                'revisi' => 'warning',
                'selesai' => 'success',
                'terlambat' => 'danger',
            ];
            
            $prioritasVariants = [
                'rendah' => 'neutral',
                'sedang' => 'info',
                'tinggi' => 'danger',
            ];
        @endphp
        
        <x-ui.table 
            :headers="['Tanggal', 'Judul', 'Pegawai', 'Unit Kerja', 'Prioritas', 'Deadline', 'Status', 'Progres', 'Terlambat', 'Aksi']"
            :empty="$daftarTugas->isEmpty()"
            :pagination="$daftarTugas->links()"
        >
            @foreach($daftarTugas as $item)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 text-ui-text-secondary">{{ optional($item->tugas->tanggal_tugas)->format('d-m-Y') ?? '-' }}</td>
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $item->tugas->judul ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ $item->pegawai->unitkerja->nama_unitkerja ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge :variant="$prioritasVariants[$item->tugas->prioritas] ?? 'primary'" styleType="soft">
                            {{ ucfirst($item->tugas->prioritas ?? '-') }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ optional($item->tugas->deadline)->format('d-m-Y') ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge :variant="$statusVariants[$item->status] ?? 'primary'" styleType="soft">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-ui-primary">{{ (int) ($item->progres_persen ?? 0) }}%</td>
                    <td class="px-4 py-3 text-center">
                        @if($item->is_terlambat)
                            <x-ui.badge variant="danger" styleType="soft">Ya</x-ui.badge>
                        @else
                            <x-ui.badge variant="success" styleType="soft">Tidak</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-ui.button variant="ghost" size="xs" leadingIcon="eye" :href="route($routePrefix . '.penugasan.show', $item->tugas_id)">
                            Detail
                        </x-ui.button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <!-- DETAIL CATATAN KEGIATAN -->
    <x-ui.card title="Detail Catatan Kegiatan" icon="file-text" class="overflow-hidden">
        @php
            $verifikasiVariants = [
                'disetujui' => 'success',
                'revisi' => 'warning',
                'menunggu_verifikasi' => 'info',
                'ditolak' => 'danger',
            ];
        @endphp
        
        <x-ui.table 
            :headers="['Tanggal', 'Pegawai', 'Tugas', 'Ringkasan Hasil', 'Status Verifikasi', 'Diverifikasi Oleh', 'Diverifikasi Pada', 'Aksi']"
            :empty="$daftarCatatan->isEmpty()"
            :pagination="$daftarCatatan->links()"
        >
            @foreach($daftarCatatan as $item)
                <tr class="border-b border-ui-border/50 hover:bg-ui-primary-soft/10">
                    <td class="px-4 py-3 text-ui-text-secondary">{{ optional($item->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</td>
                    <td class="px-4 py-3 font-semibold text-ui-text-primary">{{ $item->pegawai->user->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-primary">{{ $item->penugasan->tugas->judul ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ \Illuminate\Support\Str::limit($item->hasil_kegiatan ?? $item->deskripsi ?? '-', 80) }}</td>
                    <td class="px-4 py-3 text-center">
                        <x-ui.badge :variant="$verifikasiVariants[$item->status_verifikasi] ?? 'primary'" styleType="soft">
                            {{ $item->status_verifikasi_label }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ $item->verifier->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-ui-text-secondary">{{ optional($item->diverifikasi_at)->format('d-m-Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <x-ui.button variant="ghost" size="xs" leadingIcon="eye" :href="route($routePrefix . '.catatan_kegiatan.show', $item->id)">
                            Detail
                        </x-ui.button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>
</div>
@endsection
