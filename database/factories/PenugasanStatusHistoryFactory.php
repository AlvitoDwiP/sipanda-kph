<?php

namespace Database\Factories;

use App\Models\Penugasan;
use App\Models\PenugasanStatusHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenugasanStatusHistory>
 */
class PenugasanStatusHistoryFactory extends Factory
{
    protected $model = PenugasanStatusHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusBefore = $this->faker->randomElement(['belum_dikerjakan', 'sedang_dikerjakan', 'menunggu_verifikasi', 'revisi']);
        $statusAfter = $this->faker->randomElement(['sedang_dikerjakan', 'menunggu_verifikasi', 'selesai', 'revisi', 'dibatalkan']);
        
        $monthsToSub = $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
        $date = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

        return [
            'penugasan_id' => Penugasan::inRandomOrder()->first()?->id ?? Penugasan::factory(),
            'user_id' => User::whereIn('role', ['admin', 'kph'])->inRandomOrder()->first()?->id ?? User::factory(),
            'status_sebelum' => $statusBefore,
            'status_sesudah' => $statusAfter,
            'catatan' => $this->faker->randomElement([
                'Progres pengerjaan lapangan dimulai.',
                'Laporan awal diunggah pegawai.',
                'Tugas telah diselesaikan dengan baik.',
                'Perlu revisi pada bagian dokumentasi gambar.',
                'Tugas dibatalkan karena instruksi pimpinan.',
                null,
            ]),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
