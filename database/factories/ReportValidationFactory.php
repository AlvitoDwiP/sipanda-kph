<?php

namespace Database\Factories;

use App\Models\ReportValidation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportValidation>
 */
class ReportValidationFactory extends Factory
{
    protected $model = ReportValidation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthsToSub = $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
        $generatedAt = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

        $periodStart = (clone $generatedAt)->startOfMonth();
        $periodEnd = (clone $generatedAt)->endOfMonth();

        $code = 'REP-' . strtoupper(Str::random(8)) . '-' . $generatedAt->format('Ymd');
        $token = Str::uuid()->toString();

        return [
            'report_code' => $code,
            'validation_token' => $token,
            'report_type' => $this->faker->randomElement(['rekap_pekerjaan', 'tugas', 'catatan_kegiatan']),
            'period_type' => 'bulanan',
            'period_start' => $periodStart->format('Y-m-d'),
            'period_end' => $periodEnd->format('Y-m-d'),
            'generated_by' => User::where('role', 'admin')->orWhere('role', 'kph')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'admin']),
            'generated_at' => $generatedAt,
            'file_hash' => hash('sha256', $code . $token),
            'status' => $this->faker->boolean(95) ? 'valid' : 'revoked',
            'revoked_at' => null,
            'metadata' => [
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
                'total_records' => $this->faker->numberBetween(10, 100),
            ],
        ];
    }
}
