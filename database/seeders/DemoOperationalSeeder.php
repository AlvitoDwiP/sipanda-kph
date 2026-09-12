<?php

namespace Database\Seeders;

use App\Models\CatatanKegiatan;
use App\Models\DataDiri;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoOperationalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'nip' => '0000000000',
                'role' => 'admin',
                'status_akun' => 'aktif',
                'password' => Hash::make('12341234'),
            ]
        );

        $unitKerjaIds = $this->seedUnitKerja();
        $golonganIds = $this->seedGolongan();
        $jabatanIds = $this->seedJabatan();

        $imageSource = public_path('assets/images/avatar.png');

        for ($i = 1; $i <= 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => sprintf('pegawai%02d@sipanda.test', $i)],
                [
                    'name' => sprintf('Pegawai Demo %02d', $i),
                    'nip' => sprintf('19870000000000%02d', $i),
                    'role' => 'pegawai',
                    'status_akun' => 'aktif',
                    'password' => Hash::make('12341234'),
                    'catatan_verifikasi' => 'Data demo otomatis',
                ]
            );

            $dataDiri = DataDiri::updateOrCreate(
                ['kartu_identitas' => sprintf('32760000000000%02d', $i)],
                [
                    'no_hp' => sprintf('081234560%03d', $i),
                    'alamat' => sprintf('Jl. Demo SIPANDA No. %d, Banyuwangi', $i),
                    'tempat_lahir' => $i % 2 === 0 ? 'Banyuwangi' : 'Jember',
                    'tgl_lahir' => now()->subYears(24 + $i)->format('Y-m-d'),
                    'jenis_kelamin' => $i % 2 === 0 ? 'P' : 'L',
                    'foto' => null,
                ]
            );

            $pegawai = Pegawai::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'unitkerja_id' => $unitKerjaIds[($i - 1) % count($unitKerjaIds)],
                    'golongan_id' => $golonganIds[($i - 1) % count($golonganIds)],
                    'jabatan_id' => $jabatanIds[($i - 1) % count($jabatanIds)],
                    'status_pegawai' => 'aktif',
                    'data_diri_id' => $dataDiri->id,
                ]
            );

            $templatePath = sprintf('template_tugas/demo-template-%02d.pdf', $i);
            $laporanPath = sprintf('laporan_tugas/demo-laporan-%02d.pdf', $i);
            $catatanImagePath = sprintf('catatan_kegiatan/demo-kegiatan-%02d.png', $i);
            $progresImagePath = sprintf('progres_tugas/demo-progres-%02d.png', $i);

            $this->ensurePdfExists(
                $templatePath,
                sprintf('Template Tugas Demo %02d', $i)
            );
            $this->ensurePdfExists(
                $laporanPath,
                sprintf('Laporan Tugas Demo %02d', $i)
            );
            $this->ensureImageExists($catatanImagePath, $imageSource);
            $this->ensureImageExists($progresImagePath, $imageSource);

            $tugas = Tugas::updateOrCreate(
                ['judul' => sprintf('Tugas Operasional Demo %02d', $i)],
                [
                    'deskripsi' => sprintf(
                        'Penugasan operasional demo nomor %02d untuk kebutuhan pengujian dashboard dan alur kerja aplikasi.',
                        $i
                    ),
                    'deadline' => now()->addDays($i)->format('Y-m-d'),
                    'prioritas' => ['rendah', 'sedang', 'tinggi'][($i - 1) % 3],
                    'template' => $templatePath,
                    'user_id' => $admin->id,
                ]
            );

            [$statusPenugasan, $catatanKepegawaian, $laporan, $fotoProgres] = $this->buildPenugasanPayload(
                $i,
                $laporanPath,
                $progresImagePath
            );

            Penugasan::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'tugas_id' => $tugas->id,
                ],
                [
                    'status' => $statusPenugasan,
                    'catatan_kepegawaian' => $catatanKepegawaian,
                    'laporan' => $laporan,
                    'foto_progres' => $fotoProgres,
                ]
            );

            [$statusCatatan, $catatanStatus] = $this->buildCatatanStatus($i);

            CatatanKegiatan::updateOrCreate(
                [
                    'pegawai_id' => $pegawai->id,
                    'periode_bulan' => $i,
                    'periode_tahun' => 2026,
                ],
                [
                    'judul' => sprintf('Catatan Kegiatan Demo %02d', $i),
                    'deskripsi' => sprintf(
                        'Pelaksanaan kegiatan lapangan dan administrasi untuk pegawai demo %02d pada periode %02d/2026.',
                        $i,
                        $i
                    ),
                    'status' => $statusCatatan,
                    'catatan_status' => $catatanStatus,
                    'foto_kegiatan' => [$catatanImagePath],
                ]
            );
        }
    }

    /**
     * @return array<int, int>
     */
    private function seedUnitKerja(): array
    {
        $names = [
            'KPH BWU',
            'BKPH Barat',
            'BKPH Timur',
            'BKPH Selatan',
        ];

        return collect($names)->map(function (string $name) {
            return UnitKerja::firstOrCreate(['nama_unitkerja' => $name])->id;
        })->all();
    }

    /**
     * @return array<int, int>
     */
    private function seedGolongan(): array
    {
        $names = [
            'Pelaporan & Sistem',
            'PSDH',
            'Produksi & Ekowisata',
            'Perencanaan',
        ];

        return collect($names)->map(function (string $name) {
            return Golongan::firstOrCreate(['nama_golongan' => $name])->id;
        })->all();
    }

    /**
     * @return array<int, int>
     */
    private function seedJabatan(): array
    {
        $names = [
            'Kepala Sub Seksi',
            'Staff',
            'KBKPH',
            'Kepala Urusan',
            'KTU',
        ];

        return collect($names)->map(function (string $name) {
            return Jabatan::firstOrCreate(['nama_jabatan' => $name])->id;
        })->all();
    }

    private function ensureImageExists(string $path, string $source): void
    {
        if (! Storage::disk('public')->exists($path) && is_file($source)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        }
    }

    private function ensurePdfExists(string $path, string $title): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
        $pdf .= "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n";
        $pdf .= "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n";
        $stream = 'BT /F1 14 Tf 36 96 Td ('.$this->escapePdfText($title).') Tj ET';
        $pdf .= '4 0 obj << /Length '.strlen($stream)." >> stream\n".$stream."\nendstream endobj\n";
        $pdf .= "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n";
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        $offsets = [];

        $parts = explode("endobj\n", $pdf);
        $cursor = 0;
        foreach ($parts as $index => $part) {
            if ($index === count($parts) - 1 || trim($part) === '') {
                continue;
            }
            $offsets[] = $cursor;
            $cursor += strlen($part."endobj\n");
        }

        $xref = "xref\n0 6\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $xref .= sprintf("%010d 00000 n \n", $offset);
        }

        $body = "%PDF-1.4\n";
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n",
            '4 0 obj << /Length '.strlen($stream)." >> stream\n".$stream."\nendstream endobj\n",
            "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n",
        ];

        $offsets = [0];
        $cursor = strlen($body);
        foreach ($objects as $object) {
            $offsets[] = $cursor;
            $body .= $object;
            $cursor += strlen($object);
        }

        $xref = "xref\n0 6\n";
        $xref .= "0000000000 65535 f \n";
        for ($i = 1; $i <= 5; $i++) {
            $xref .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $trailer = "trailer << /Size 6 /Root 1 0 R >>\nstartxref\n".strlen($body)."\n%%EOF";

        Storage::disk('public')->put($path, $body.$xref.$trailer);
    }

    /**
     * @return array{string, string|null, string|null, string|null}
     */
    private function buildPenugasanPayload(int $index, string $laporanPath, string $progresImagePath): array
    {
        if ($index % 3 === 0) {
            return [
                'selesai',
                'Pekerjaan selesai sesuai target.',
                $laporanPath,
                json_encode([$progresImagePath]),
            ];
        }

        if ($index % 3 === 1) {
            return [
                'proses',
                'Sedang dalam proses pengerjaan.',
                null,
                json_encode([$progresImagePath]),
            ];
        }

        return [
            'baru',
            null,
            null,
            null,
        ];
    }

    /**
     * @return array{string, string|null}
     */
    private function buildCatatanStatus(int $index): array
    {
        $statuses = ['draft', 'ajukan', 'setuju', 'tolak'];
        $status = $statuses[($index - 1) % count($statuses)];

        return match ($status) {
            'setuju' => [$status, 'Catatan kegiatan disetujui untuk arsip.'],
            'tolak' => [$status, 'Perlu revisi deskripsi dan lampiran kegiatan.'],
            default => [$status, null],
        };
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $text
        );
    }
}
