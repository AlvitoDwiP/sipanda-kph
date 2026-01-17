<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catatan Kegiatan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: bold;
        }

        hr {
            margin: 12px 0;
        }

        /* ===== FOTO ===== */
        .foto {
            width: 250px;        /* FOTO DIPERKECIL */
            height: auto;        /* PROPORSI TETAP */
            margin: 8px 0;
            display: block;
        }

        .foto-wrapper td {
            padding: 6px;
        }
    </style>
</head>
<body>

    <h3>CATATAN KEGIATAN PEGAWAI</h3>

    <!-- DATA PEGAWAI -->
    <table>
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $catatan->pegawai->user->name }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>: {{ $catatan->pegawai->user->nip }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>: {{ $catatan->pegawai->jabatan->nama_jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Golongan</td>
            <td>: {{ $catatan->pegawai->golongan->nama_golongan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td>: {{ $catatan->pegawai->unitkerja->nama_unitkerja }}</td>
        </tr>
        <tr>
            <td class="label">Periode</td>
            <td>
                :
                {{ \Carbon\Carbon::create()
                    ->month($catatan->periode_bulan)
                    ->translatedFormat('F') }}
                {{ $catatan->periode_tahun }}
            </td>
        </tr>
    </table>

    <hr>

    <!-- JUDUL -->
    <strong>Judul Kegiatan</strong><br>
    {{ $catatan->judul }}

    <hr>

    <!-- DESKRIPSI -->
    <strong>Uraian Kegiatan</strong><br>
    {!! nl2br(e($catatan->deskripsi)) !!}

    <hr>

    <!-- FOTO -->
    <strong>Foto Kegiatan</strong><br><br>

    @php
        $fotos = is_string($catatan->foto_kegiatan)
            ? json_decode($catatan->foto_kegiatan, true)
            : $catatan->foto_kegiatan;
    @endphp

    @if (!empty($fotos))
        <table class="foto-wrapper">
            <tr>
                @foreach ($fotos as $index => $foto)
                    <td>
                        <img
                            src="{{ public_path('storage/' . $foto) }}"
                            class="foto">
                    </td>

                    @if (($index + 1) % 2 === 0)
                        </tr><tr>
                    @endif
                @endforeach
            </tr>
        </table>
    @else
        <em>Tidak ada foto kegiatan.</em>
    @endif

</body>
</html>
