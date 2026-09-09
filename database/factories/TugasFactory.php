<?php

namespace Database\Factories;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class TugasFactory extends Factory
{
    protected $model = Tugas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Support weighted random date in last 12 months
        $monthsToSub = $this->faker->randomElement([0, 1, 1, 2, 2, 3, 4, 5, 5, 6, 7, 8, 9, 9, 10, 11, 12]);
        $tanggalTugas = now()->subMonths($monthsToSub)->subDays(rand(1, 28));
        $deadline = (clone $tanggalTugas)->addDays(rand(3, 14));

        $prioritas = $this->faker->randomElement(['rendah', 'sedang', 'tinggi']);
        $id = $this->faker->unique()->numberBetween(1, 10000);
        $templatePath = sprintf('template_tugas/template-%d.pdf', $id);

        return [
            'user_id' => User::where('role', 'admin')->orWhere('role', 'kph')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'admin']),
            'judul' => $this->faker->randomElement([
                'Patroli Perlindungan Hutan RPH ' . $this->faker->firstName(),
                'Inventarisasi Tegakan Pohon Blok ' . $this->faker->randomLetter(),
                'Sosialisasi Pencegahan Karhutla Terpadu',
                'Monitoring Batas Kawasan Hutan Lindung',
                'Penyusunan Rencana Kerja Tahunan (RKT)',
                'Rehabilitasi Lahan Kritis Lereng Gunung',
                'Pengukuran Debit Air Sumber Air RPH',
                'Penyuluhan Kelompok Tani Hutan (KTH)',
                'Pemeriksaan Laporan Produksi Kayu Sengon',
                'Patroli Pengamanan Satwa Liar',
            ]) . ' ' . $this->faker->year(),
            'deskripsi' => $this->faker->paragraph(2),
            'tanggal_tugas' => $tanggalTugas->format('Y-m-d'),
            'deadline' => $deadline->format('Y-m-d'),
            'prioritas' => $prioritas,
            'template' => $templatePath,
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Tugas $tugas) {
            $this->ensurePdfExists($tugas->template, 'Template: ' . $tugas->judul);
        });
    }

    private function ensurePdfExists(string $path, string $title): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        $stream = "BT /F1 14 Tf 36 96 Td (" . str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $title) . ") Tj ET";
        $pdf = "%PDF-1.4\n";
        $objects = [
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n",
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n",
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n",
            "4 0 obj << /Length " . strlen($stream) . " >> stream\n" . $stream . "\nendstream endobj\n",
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

        $trailer = "trailer << /Size 6 /Root 1 0 R >>\nstartxref\n" . strlen($pdf) . "\n%%EOF";
        Storage::disk('public')->put($path, $pdf . $xref . $trailer);
    }
}
