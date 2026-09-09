<?php

namespace Database\Factories;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    protected $model = Log::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthsToSub = $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
        $date = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'aksi' => $this->faker->randomElement([
                'Melakukan login ke dalam sistem.',
                'Mengunduh rekap pekerjaan PDF.',
                'Menambahkan catatan kegiatan harian baru.',
                'Melakukan persetujuan penugasan pegawai.',
                'Mengubah password akun.',
                'Melakukan update data profil data diri.',
                'Mengunggah file laporan penugasan.',
                'Melakukan scanning QR Code pegawai.',
                'Memperbarui status tugas menjadi sedang dikerjakan.',
                'Mengirimkan pengajuan catatan kegiatan untuk diverifikasi.',
            ]),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
