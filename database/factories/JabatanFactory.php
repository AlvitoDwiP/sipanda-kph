<?php

namespace Database\Factories;

use App\Models\Jabatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jabatan>
 */
class JabatanFactory extends Factory
{
    protected $model = Jabatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_jabatan' => $this->faker->unique()->randomElement([
                'Kepala Sub Seksi (KSS)',
                'KTU (Kepala Tata Usaha)',
                'KBKPH (Kepala Bagian Kesatuan Pemangkuan Hutan)',
                'KRPH (Kepala Resort Pemangkuan Hutan)',
                'Staf Perencanaan KPH',
                'Staf Produksi & Ekowisata',
                'Staf PSDH & Kehutanan',
                'Staf Pelaporan & Sistem',
                'Polisi Hutan (Polhut)',
                'Penyuluh Kehutanan',
                'Pengawas Lapangan',
                'Operator Sistem Informasi',
            ]),
        ];
    }
}
