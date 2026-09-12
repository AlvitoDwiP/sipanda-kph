<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penugasan>
 */
class PenugasanFactory extends Factory
{
    protected $model = Penugasan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusRand = $this->faker->numberBetween(1, 100);

        if ($statusRand <= 10) {
            // 10% Draft (belum_dikerjakan)
            $status = 'belum_dikerjakan';
        } elseif ($statusRand <= 35) {
            // 25% Diproses
            $status = $this->faker->randomElement(['sedang_dikerjakan', 'menunggu_verifikasi']);
        } elseif ($statusRand <= 90) {
            // 55% Selesai
            $status = 'selesai';
        } else {
            // 10% Ditolak/Lainnya
            $status = $this->faker->randomElement(['revisi', 'dibatalkan']);
        }

        $id = $this->faker->unique()->numberBetween(1, 10000);
        $progresPersen = 0;
        $laporan = null;
        $fotoProgres = null;
        $selesaiAt = null;
        $alasanPembatalan = null;
        $catatanRevisi = null;
        $catatanProgres = null;

        if ($status === 'sedang_dikerjakan') {
            $progresPersen = $this->faker->numberBetween(10, 90);
            $fotoProgres = [sprintf('progres_tugas/progres-%d.png', $id)];
            $catatanProgres = 'Pekerjaan lapangan telah berjalan sebagian.';
        } elseif ($status === 'menunggu_verifikasi') {
            $progresPersen = 100;
            $laporan = sprintf('laporan_tugas/laporan-%d.pdf', $id);
            $fotoProgres = [sprintf('progres_tugas/progres-%d.png', $id)];
            $catatanProgres = 'Laporan selesai dikerjakan, menunggu verifikasi.';
        } elseif ($status === 'selesai') {
            $progresPersen = 100;
            $laporan = sprintf('laporan_tugas/laporan-%d.pdf', $id);
            $fotoProgres = [sprintf('progres_tugas/progres-%d.png', $id)];
            $catatanProgres = 'Semua tahapan selesai dikonfirmasi oleh pengawas.';
        } elseif ($status === 'revisi') {
            $progresPersen = $this->faker->numberBetween(60, 90);
            $laporan = sprintf('laporan_tugas/laporan-%d.pdf', $id);
            $fotoProgres = [sprintf('progres_tugas/progres-%d.png', $id)];
            $catatanRevisi = 'Revisi: Perbaiki koordinat dokumentasi di lapangan.';
        } elseif ($status === 'dibatalkan') {
            $progresPersen = $this->faker->numberBetween(0, 30);
            $alasanPembatalan = 'Dibatalkan karena tumpang tindih penugasan kedinasan lain.';
        }

        return [
            'pegawai_id' => Pegawai::inRandomOrder()->first()?->id ?? Pegawai::factory(),
            'tugas_id' => Tugas::inRandomOrder()->first()?->id ?? Tugas::factory(),
            'status' => $status,
            'progres_persen' => $progresPersen,
            'catatan_kepegawaian' => $this->faker->randomElement([
                'Kinerja sesuai instruksi.',
                'Perlu koordinasi dengan BKPH setempat.',
                'Dilaksanakan secara tim.',
                null,
            ]),
            'catatan_progres' => $catatanProgres,
            'catatan_revisi' => $catatanRevisi,
            'progres_updated_at' => $this->faker->dateTimeBetween('-11 months', 'now'),
            'selesai_at' => $status === 'selesai' ? $this->faker->dateTimeBetween('-11 months', 'now') : null,
            'alasan_pembatalan' => $alasanPembatalan,
            'laporan' => $laporan,
            'foto_progres' => $fotoProgres,
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Penugasan $penugasan) {
            $imageSource = public_path('assets/images/avatar.png');

            if ($penugasan->laporan) {
                $this->ensurePdfExists($penugasan->laporan, 'Laporan: Tugas #'.$penugasan->tgl_lahir);
            }
            if ($penugasan->foto_progres) {
                foreach ($penugasan->foto_progres as $path) {
                    $this->ensureImageExists($path, $imageSource);
                }
            }

            // Sync Penugasan date with Tugas date
            if ($penugasan->tugas) {
                $tugasDate = $penugasan->tugas->tanggal_tugas;
                $penugasan->update([
                    'created_at' => $tugasDate,
                    'updated_at' => $tugasDate,
                    'progres_updated_at' => $penugasan->status === 'belum_dikerjakan' ? null : $tugasDate->addDays(rand(1, 4)),
                    'selesai_at' => $penugasan->status === 'selesai' ? $tugasDate->addDays(rand(5, 10)) : null,
                ]);
            }
        });
    }

    private function ensurePdfExists(string $path, string $title): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        $stream = 'BT /F1 14 Tf 36 96 Td ('.str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $title).') Tj ET';
        $pdf = "%PDF-1.4\n";
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n",
            '4 0 obj << /Length '.strlen($stream)." >> stream\n".$stream."\nendstream endobj\n",
            "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n",
        ];

        $offsets = [0];
        $cursor = strlen($pdf);
        foreach ($objects as $object) {
            $offsets[] = $cursor;
            $pdf .= $object;
            $cursor += strlen($object);
        }

        $xref = "xref\n0 6\n";
        $xref .= "0000000000 65535 f \n";
        for ($i = 1; $i <= 5; $i++) {
            $xref .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $trailer = "trailer << /Size 6 /Root 1 0 R >>\nstartxref\n".strlen($pdf)."\n%%EOF";
        Storage::disk('public')->put($path, $pdf.$xref.$trailer);
    }

    private function ensureImageExists(string $path, string $source): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        if (is_file($source)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        } else {
            // fallback: empty png
            Storage::disk('public')->put($path, '');
        }
    }
}
