<?php

namespace Database\Factories;

use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CatatanKegiatan>
 */
class CatatanKegiatanFactory extends Factory
{
    protected $model = CatatanKegiatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthsToSub = $this->faker->randomElement([0, 1, 1, 2, 2, 3, 4, 5, 5, 6, 7, 8, 9, 9, 10, 11, 12]);
        $date = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

        $statusRand = $this->faker->randomElement(['menunggu', 'revisi', 'disetujui', 'ditolak', 'draft']);

        $status = 'draft';
        $statusVerifikasi = 'menunggu_verifikasi';
        $catatanVerifikasi = null;
        $catatanStatus = null;
        $diverifikasiOleh = null;
        $diverifikasiAt = null;

        if ($statusRand === 'menunggu') {
            $status = 'ajukan';
            $statusVerifikasi = 'menunggu_verifikasi';
        } elseif ($statusRand === 'revisi') {
            $status = 'tolak';
            $statusVerifikasi = 'revisi';
            $catatanVerifikasi = 'Lengkapi deskripsi detail volume kayu/hasil kegiatan.';
            $catatanStatus = 'Revisi deskripsi volume.';
        } elseif ($statusRand === 'disetujui') {
            $status = 'setuju';
            $statusVerifikasi = 'disetujui';
            $catatanVerifikasi = 'Data dan dokumentasi lengkap.';
            $catatanStatus = 'Laporan disetujui.';
            $diverifikasiOleh = User::where('role', 'kph')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'kph']);
            $diverifikasiAt = (clone $date)->addDays(rand(1, 3));
        } elseif ($statusRand === 'ditolak') {
            $status = 'tolak';
            $statusVerifikasi = 'ditolak';
            $catatanVerifikasi = 'Dokumentasi tidak valid atau tumpang tindih.';
            $catatanStatus = 'Laporan ditolak.';
            $diverifikasiOleh = User::where('role', 'kph')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'kph']);
            $diverifikasiAt = (clone $date)->addDays(rand(1, 3));
        }

        $id = $this->faker->unique()->numberBetween(1, 10000);
        $fotoKegiatan = [sprintf('catatan_kegiatan/kegiatan-%d.png', $id)];

        return [
            'pegawai_id' => Pegawai::inRandomOrder()->first()?->id ?? Pegawai::factory(),
            'penugasan_id' => null,
            'periode_bulan' => (int) $date->format('m'),
            'periode_tahun' => (int) $date->format('Y'),
            'tanggal_kegiatan' => $date->format('Y-m-d'),
            'judul' => $this->faker->randomElement([
                'Laporan Harian Penjagaan Pos RPH Pos I',
                'Pembersihan Sekat Bakar Hutan Produksi Blok Barat',
                'Pemasangan Papan Himbauan Hutan Lindung Petak 12',
                'Pendampingan Mahasiswa Penelitian Kehutanan UGM',
                'Pemeriksaan Kesehatan Bibit Sengon Persemaian Licin',
                'Operasi Pengamanan Patroli Gabungan Wilayah BKPH',
                'Penerimaan Kunjungan Perhutani Regional Divre Jatim',
                'Pemeliharaan Tanaman Agroforestri Kopi BKPH Selatan',
            ]),
            'deskripsi' => $this->faker->paragraph(2),
            'hasil_kegiatan' => $this->faker->sentence(10),
            'kendala' => $this->faker->randomElement([
                'Cuaca hujan lebat di area lereng hutan.',
                'Akses jalan menuju petak hutan terhambat longsor ringan.',
                'Sinyal telekomunikasi lemah untuk unggah data real-time.',
                null,
            ]),
            'status' => $status,
            'status_verifikasi' => $statusVerifikasi,
            'catatan_verifikasi' => $catatanVerifikasi,
            'catatan_status' => $catatanStatus,
            'diverifikasi_oleh' => $diverifikasiOleh,
            'diverifikasi_at' => $diverifikasiAt,
            'foto_kegiatan' => $fotoKegiatan,
        ];
    }

    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (CatatanKegiatan $catatan) {
            $imageSource = public_path('assets/images/avatar.png');
            if ($catatan->foto_kegiatan) {
                foreach ($catatan->foto_kegiatan as $path) {
                    $this->ensureImageExists($path, $imageSource);
                }
            }
        });
    }

    private function ensureImageExists(string $path, string $source): void
    {
        if (Storage::disk('public')->exists($path)) {
            return;
        }

        if (is_file($source)) {
            Storage::disk('public')->put($path, file_get_contents($source));
        } else {
            Storage::disk('public')->put($path, '');
        }
    }
}
