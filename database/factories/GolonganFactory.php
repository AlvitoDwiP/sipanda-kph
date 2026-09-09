<?php

namespace Database\Factories;

use App\Models\Golongan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Golongan>
 */
class GolonganFactory extends Factory
{
    protected $model = Golongan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_golongan' => $this->faker->unique()->randomElement([
                'Pembina Utama Madya (IV/d)',
                'Pembina Utama Muda (IV/c)',
                'Pembina Tingkat I (IV/b)',
                'Pembina (IV/a)',
                'Penata Tingkat I (III/d)',
                'Penata (III/c)',
                'Penata Muda Tingkat I (III/b)',
                'Penata Muda (III/a)',
                'Pengatur Tingkat I (II/d)',
                'Pengatur (II/c)',
                'Pengatur Muda Tingkat I (II/b)',
                'Pengatur Muda (II/a)',
            ]),
        ];
    }
}
