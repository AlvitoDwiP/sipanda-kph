<?php

namespace Database\Factories;

use App\Models\DisplayScanLog;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DisplayScanLog>
 */
class DisplayScanLogFactory extends Factory
{
    protected $model = DisplayScanLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthsToSub = $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
        $scannedAt = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

        $status = $this->faker->randomElement(['success', 'invalid_token', 'inactive_employee', 'empty_task']);

        $message = match ($status) {
            'success' => 'Scan QR Code pegawai berhasil.',
            'invalid_token' => 'Token QR Code tidak valid atau kedaluwarsa.',
            'inactive_employee' => 'Pegawai tidak aktif.',
            'empty_task' => 'Pegawai tidak memiliki penugasan aktif hari ini.',
        };

        $pegawai = Pegawai::inRandomOrder()->first();

        return [
            'scanned_at' => $scannedAt,
            'qr_token_hash' => $pegawai ? hash('sha256', $pegawai->qr_token) : hash('sha256', 'PGW-DUMMY12345'),
            'pegawai_id' => $status === 'invalid_token' ? null : ($pegawai?->id ?? Pegawai::factory()),
            'scanned_by' => User::where('role', 'kph')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'kph']),
            'status' => $status,
            'task_count' => $status === 'success' ? $this->faker->numberBetween(1, 5) : 0,
            'message' => $message,
            'created_at' => $scannedAt,
            'updated_at' => $scannedAt,
        ];
    }
}
