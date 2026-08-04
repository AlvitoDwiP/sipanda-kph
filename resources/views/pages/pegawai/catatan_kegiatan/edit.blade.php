@extends('layouts.master')

@section('title', 'Perbaiki Catatan Kegiatan')

@section('content')

<x-ui.page-header title="Perbaiki Catatan Kegiatan" subtitle="Perbaiki catatan kegiatan Anda berdasarkan feedback / catatan verifikasi.">
    <x-slot name="breadcrumbs">
        <x-ui.breadcrumb />
    </x-slot>
    <x-slot name="actions">
        <x-ui.button variant="ghost" size="sm" leadingIcon="arrow-left" :href="route('pegawai.catatan_kegiatan.show', $catatan_kegiatan->id)">
            Kembali
        </x-ui.button>
    </x-slot>
</x-ui.page-header>

<div class="space-y-6">
    <!-- FEEDBACK VERIFIKATOR ALERT -->
    <x-ui.alert variant="warning" :dismissible="false" title="Catatan Verifikasi / Alasan Revisi" :description="$catatan_kegiatan->catatan_verifikasi ?? 'Harap tinjau kembali data kegiatan yang Anda kirim.'" />

    <!-- FORM EDIT -->
    <x-ui.card>
        <form method="POST" action="{{ route('pegawai.catatan_kegiatan.update', $catatan_kegiatan) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Tanggal Kegiatan -->
                <div>
                    <x-ui.input 
                        type="date"
                        name="tanggal_kegiatan"
                        label="Tanggal Kegiatan"
                        value="{{ old('tanggal_kegiatan', optional($catatan_kegiatan->tanggal_kegiatan)->toDateString()) }}"
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
                        placeholder="Uraikan rincian kegiatan..."
                        required
                        rows="4"
                        :error="$errors->first('deskripsi')"
                    >{{ old('deskripsi', $catatan_kegiatan->deskripsi) }}</x-ui.input>
                </div>

                <!-- Hasil Kegiatan -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="hasil_kegiatan"
                        label="Hasil Kegiatan (Output)"
                        placeholder="Uraikan hasil yang dicapai..."
                        required
                        rows="4"
                        :error="$errors->first('hasil_kegiatan')"
                    >{{ old('hasil_kegiatan', $catatan_kegiatan->hasil_kegiatan) }}</x-ui.input>
                </div>

                <!-- Kendala -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="textarea"
                        name="kendala"
                        label="Kendala / Hambatan (Opsional)"
                        placeholder="Sebutkan kendala..."
                        rows="3"
                        :error="$errors->first('kendala')"
                    >{{ old('kendala', $catatan_kegiatan->kendala) }}</x-ui.input>
                </div>

                <!-- Bukti Pendukung Saat Ini -->
                @if ($catatan_kegiatan->foto_kegiatan)
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-semibold text-ui-text-primary">Berkas Lampiran Saat Ini (Centang untuk menghapus)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($catatan_kegiatan->foto_kegiatan as $foto)
                                <div class="border border-ui-border rounded-xl p-3 bg-slate-50 flex flex-col justify-between gap-3 text-xs">
                                    <div class="truncate">
                                        <x-ui.button variant="outline" size="xs" leadingIcon="external-link" :href="asset('storage/' . $foto)" target="_blank">
                                            Buka Berkas
                                        </x-ui.button>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <input type="checkbox" name="hapus_foto[]" value="{{ $foto }}" id="hapus_{{ md5($foto) }}" class="rounded text-ui-primary focus:ring-ui-primary/20">
                                        <label for="hapus_{{ md5($foto) }}" class="text-[11px] text-ui-text-secondary select-none cursor-pointer">Hapus berkas ini</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tambah Bukti Pendukung Baru -->
                <div class="md:col-span-2">
                    <x-ui.input 
                        type="file"
                        name="foto_kegiatan[]"
                        label="Tambah Bukti Kegiatan / Foto Pendukung Baru (Bisa pilih beberapa)"
                        multiple
                        accept="image/*,application/pdf"
                        :error="$errors->first('foto_kegiatan.*')"
                    />
                </div>
            </div>

            <!-- Actions -->
            <x-slot name="footer">
                <x-ui.button variant="ghost" size="sm" :href="route('pegawai.catatan_kegiatan.show', $catatan_kegiatan->id)">
                    Batal
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" size="sm" leadingIcon="send">
                    Kirim Ulang Verifikasi
                </x-ui.button>
            </x-slot>
        </form>
    </x-ui.card>
</div>

@endsection
