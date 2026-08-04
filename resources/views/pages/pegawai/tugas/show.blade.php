@extends('layouts.master')

@section('title', 'Detail Tugas Saya')

@section('content')

@if (session('success'))
    <x-ui.alert variant="success" class="mb-5" :description="session('success')" />
@endif
@if (session('error'))
    <x-ui.alert variant="danger" class="mb-5" :description="session('error')" />
@endif

<x-ui.page-header title="Detail Tugas" subtitle="Rincian penugasan harian, perkembangan progres, dan aksi pelaporan.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('pegawai.tugas.index')">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <!-- METADATA TUGAS -->
    <x-ui.card title="Rincian Tugas Harian" icon="file-text">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 text-xs sm:text-sm">
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Judul Tugas</span>
                <span class="font-bold text-ui-text-primary text-xs sm:text-sm">{{ $tugas->judul }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Pemberi Tugas</span>
                <span class="font-medium text-ui-text-primary">{{ $tugas->user->name ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Prioritas</span>
                <span>
                    @if ($tugas->prioritas === 'rendah')
                        <x-ui.badge variant="success" size="sm">Rendah</x-ui.badge>
                    @elseif ($tugas->prioritas === 'sedang')
                        <x-ui.badge variant="warning" size="sm">Sedang</x-ui.badge>
                    @elseif ($tugas->prioritas === 'tinggi')
                        <x-ui.badge variant="danger" size="sm">Tinggi</x-ui.badge>
                    @else
                        <x-ui.badge variant="neutral" size="sm">-</x-ui.badge>
                    @endif
                </span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tanggal Mulai</span>
                <span class="font-medium text-ui-text-primary">{{ optional($tugas->tanggal_tugas)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Batas Akhir (Deadline)</span>
                <span class="font-medium text-ui-text-primary">{{ optional($tugas->deadline)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Kondisi Pengerjaan</span>
                <span>
                    @if($penugasanSaya->is_terlambat)
                        <x-ui.badge variant="danger" size="sm">Terlambat</x-ui.badge>
                    @else
                        <x-ui.badge variant="success" size="sm">Normal</x-ui.badge>
                    @endif
                </span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Status Tugas Saya</span>
                <span class="capitalize font-semibold">
                    @if($penugasanSaya->status === 'selesai')
                        <x-ui.badge variant="success" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                    @elseif(in_array($penugasanSaya->status, ['proses', 'menunggu_verifikasi']))
                        <x-ui.badge variant="warning" size="sm">{{ str_replace('_', ' ', $penugasanSaya->status) }}</x-ui.badge>
                    @elseif($penugasanSaya->status === 'dibatalkan')
                        <x-ui.badge variant="neutral" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                    @else
                        <x-ui.badge variant="primary" size="sm">{{ $penugasanSaya->status }}</x-ui.badge>
                    @endif
                </span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Progres Pengerjaan</span>
                <span class="font-semibold text-ui-text-primary">{{ $penugasanSaya->progres_persen ?? 0 }}%</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Dokumen Acuan</span>
                <div>
                    @if ($tugas->template)
                        <x-ui.button variant="secondary" size="xs" leadingIcon="file-text" :href="asset('storage/' . $tugas->template)" target="_blank">
                            Unduh Template
                        </x-ui.button>
                    @else
                        <span class="text-ui-muted font-medium">-</span>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Instruksi Tugas</span>
                <div class="bg-ui-primary-soft/30 p-3.5 rounded-lg border border-ui-border text-ui-text-primary leading-relaxed">
                    {{ $tugas->deskripsi }}
                </div>
            </div>
            @if($penugasanSaya->catatan_progres)
                <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                    <span class="text-[10px] sm:text-xs text-ui-text-secondary">Catatan Progres Terakhir</span>
                    <span class="font-medium text-ui-text-primary">{{ $penugasanSaya->catatan_progres }}</span>
                </div>
            @endif
            @if($penugasanSaya->catatan_revisi)
                <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                    <span class="text-[10px] sm:text-xs text-ui-text-secondary">Catatan Revisi / Evaluasi</span>
                    <span class="font-medium text-ui-danger bg-ui-danger-soft/20 p-3 rounded-lg border border-ui-danger/10">{{ $penugasanSaya->catatan_revisi }}</span>
                </div>
            @endif
        </div>
    </x-ui.card>

    <!-- FORM & AKSI -->
    <x-ui.card title="Aksi Pelaporan Tugas" icon="edit-3">
        <div class="space-y-4 text-xs sm:text-sm">
            @if (in_array($penugasanSaya->status, ['sedang_dikerjakan', 'revisi', 'menunggu_verifikasi', 'proses']))
                <div class="pb-4 border-b border-ui-border/50">
                    <x-ui.button variant="primary" size="sm" leadingIcon="file-plus" :href="route('pegawai.tugas.catatan.create', $penugasanSaya->id)">
                        Buat Catatan Kegiatan
                    </x-ui.button>
                </div>
            @endif

            @if (in_array($penugasanSaya->status, ['selesai']))
                <x-ui.alert variant="success" :dismissible="false" description="Tugas sudah selesai diverifikasi dan tidak dapat diubah lagi." />
            @elseif (in_array($penugasanSaya->status, ['dibatalkan']))
                <x-ui.alert variant="danger" :dismissible="false" description="Tugas telah dibatalkan." />
            @else
                @if (in_array($penugasanSaya->status, ['belum_dikerjakan', 'baru']))
                    <form method="POST" action="{{ route('pegawai.tugas.mulai', $penugasanSaya->id) }}">
                        @csrf
                        <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="play">
                            Mulai Kerjakan Tugas
                        </x-ui.button>
                    </form>
                @endif

                @if (in_array($penugasanSaya->status, ['sedang_dikerjakan', 'revisi', 'proses']))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Update Progres Form -->
                        <form method="POST" action="{{ route('pegawai.tugas.progres', $penugasanSaya->id) }}" class="space-y-4 bg-slate-50 border rounded-xl p-4">
                            @csrf
                            <h4 class="font-semibold text-ui-text-primary text-xs uppercase tracking-wider mb-2">Update Perkembangan</h4>
                            
                            <x-ui.input 
                                type="number"
                                name="progres_persen"
                                label="Progres Pengerjaan (%)"
                                min="0"
                                max="100"
                                value="{{ old('progres_persen', $penugasanSaya->progres_persen ?? 0) }}"
                                required
                            />
                            
                            <x-ui.input 
                                type="textarea"
                                name="catatan_progres"
                                label="Catatan Perkembangan"
                                placeholder="Apa saja yang telah diselesaikan?"
                                required
                                rows="3"
                            >{{ old('catatan_progres', $penugasanSaya->catatan_progres) }}</x-ui.input>

                            <x-ui.button type="submit" variant="secondary" size="sm" leadingIcon="save">
                                Perbarui Progres
                            </x-ui.button>
                        </form>

                        <!-- Verifikasi Action Card -->
                        <div class="bg-ui-primary-soft/20 border border-ui-primary/10 rounded-xl p-4 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-ui-text-primary text-xs uppercase tracking-wider mb-2">Kirim Hasil Kerja</h4>
                                <p class="text-xs text-ui-text-secondary leading-relaxed mb-4">
                                    Jika Anda telah menyelesaikan pengerjaan tugas harian ini, klik tombol di bawah untuk mengajukan verifikasi laporan kepada pemberi tugas.
                                </p>
                            </div>
                            <form method="POST" action="{{ route('pegawai.tugas.kirim-verifikasi', $penugasanSaya->id) }}">
                                @csrf
                                <x-ui.button type="submit" variant="success" size="sm" leadingIcon="check-circle" fullWidth>
                                    Ajukan Verifikasi Sekarang
                                </x-ui.button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </x-ui.card>

    <!-- CATATAN KEGIATAN TERKAIT -->
    <x-ui.card title="Catatan Kegiatan Terkait Tugas" icon="list">
        <div class="space-y-3">
            @forelse($catatanTerkait as $catatan)
                <div class="border border-ui-border rounded-xl p-4 hover:border-ui-primary/30 transition-all flex items-start justify-between gap-4 text-xs sm:text-sm text-left">
                    <div class="space-y-1">
                        <div class="font-semibold text-ui-text-primary flex items-center gap-2">
                            <span>Kegiatan Tanggal: {{ optional($catatan->tanggal_kegiatan)->format('d-m-Y') ?? '-' }}</span>
                            <span class="text-xs">
                                @if($catatan->status_verifikasi === 'disetujui')
                                    <x-ui.badge variant="success" size="sm">Disetujui</x-ui.badge>
                                @elseif(in_array($catatan->status_verifikasi, ['revisi', 'menunggu_verifikasi']))
                                    <x-ui.badge variant="warning" size="sm">{{ str_replace('_', ' ', $catatan->status_verifikasi) }}</x-ui.badge>
                                @elseif($catatan->status_verifikasi === 'ditolak')
                                    <x-ui.badge variant="danger" size="sm">Ditolak</x-ui.badge>
                                @else
                                    <x-ui.badge variant="neutral" size="sm">{{ $catatan->status_verifikasi }}</x-ui.badge>
                                @endif
                            </span>
                        </div>
                        <p class="text-ui-text-secondary leading-relaxed">{{ \Illuminate\Support\Str::limit($catatan->hasil_kegiatan ?? $catatan->deskripsi, 200) }}</p>
                        @if($catatan->catatan_verifikasi)
                            <div class="text-ui-warning bg-ui-warning-soft/20 px-3 py-1 rounded-lg border border-ui-warning/10 inline-block font-semibold mt-1">
                                Catatan Verifikasi: {{ $catatan->catatan_verifikasi }}
                            </div>
                        @endif
                    </div>
                    <x-ui.button variant="ghost" size="xs" :href="route('pegawai.catatan_kegiatan.show', $catatan->id)">
                        Detail
                    </x-ui.button>
                </div>
            @empty
                <x-ui.empty-state 
                    icon="clipboard" 
                    title="Belum Ada Catatan Kegiatan" 
                    description="Belum ada laporan harian / catatan kegiatan yang terkait dengan tugas harian ini." 
                />
            @endforelse
        </div>
    </x-ui.card>
</div>

@endsection
