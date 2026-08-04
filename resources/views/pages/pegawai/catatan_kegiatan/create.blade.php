@extends('layouts.master')

@section('title', 'Buat Catatan Kegiatan')

@section('content')

<x-ui.page-header title="Buat Catatan Kegiatan" subtitle="Catat detail aktivitas harian dan hasil kerja Anda untuk diverifikasi.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('pegawai.tugas.show', $penugasan->tugas_id)">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <!-- METADATA TUGAS ACUAN -->
    <x-ui.card title="Referensi Tugas Utama" icon="file-text" variant="flat">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs sm:text-sm">
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Tugas Utama</span>
                <span class="font-bold text-ui-primary">{{ $penugasan->tugas->judul ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Batas Akhir (Deadline)</span>
                <span class="font-semibold text-ui-text-primary">{{ optional($penugasan->tugas->deadline)->format('d-m-Y') ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-0.5 sm:col-span-2 md:col-span-3">
                <span class="text-[10px] sm:text-xs text-ui-text-secondary">Instruksi / Detail Tugas</span>
                <span class="text-ui-text-primary leading-relaxed bg-white border p-3 rounded-lg">{{ $penugasan->tugas->deskripsi ?? '-' }}</span>
            </div>
        </div>
    </x-ui.card>

    <!-- FORM INPUT CATATAN -->
    <x-ui.card>
        <form method="POST" action="{{ route('pegawai.tugas.catatan.store', $penugasan->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Tanggal Kegiatan -->
                <div>
                    <x-ui.input 
                        type="date"
                        name="tanggal_kegiatan"
                        label="Tanggal Kegiatan"
                        value="{{ old('tanggal_kegiatan', now()->toDateString()) }}"
                        required
                        :error="$errors->first('tanggal_kegiatan')"
                    />
                </div>

                <!-- Spacer -->
                <div class="hidden md:block"></div>

                <!-- Deskripsi Kegiatan -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="deskripsi"
                        label="Deskripsi Rincian Kegiatan"
                        placeholder="Uraikan secara jelas apa saja kegiatan yang dilakukan..."
                        required
                        rows="4"
                        :error="$errors->first('deskripsi')"
                    >{{ old('deskripsi') }}</x-ui.input>
                </div>

                <!-- Hasil Kegiatan -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="hasil_kegiatan"
                        label="Hasil Kegiatan (Output)"
                        placeholder="Tuliskan output, laporan, data, atau target fisik yang tercapai..."
                        required
                        rows="4"
                        :error="$errors->first('hasil_kegiatan')"
                    >{{ old('hasil_kegiatan') }}</x-ui.input>
                </div>

                <!-- Kendala -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="kendala"
                        label="Kendala / Hambatan (Opsional)"
                        placeholder="Sebutkan kendala di lapangan jika ada..."
                        rows="3"
                        :error="$errors->first('kendala')"
                    >{{ old('kendala') }}</x-ui.input>
                </div>

                <!-- Bukti Pendukung -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="file"
                        name="foto_kegiatan[]"
                        label="Bukti Kegiatan / Foto Pendukung (Bisa pilih beberapa)"
                        multiple
                        accept="image/*,application/pdf"
                        :error="$errors->first('foto_kegiatan.*')"
                    />
                </div>
            </div>

            <!-- Actions -->
            <x-slot name="footer">
                <x-ui.button variant="ghost" size="sm" :href="route('pegawai.tugas.show', $penugasan->tugas_id)">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="send">
                    Kirim Catatan Kegiatan
                </x-ui.button>
            </x-slot>
        </form>
    </x-ui.card>
</div>

@endsection
