<?php

namespace Database\Factories;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKerja>
 */
class UnitKerjaFactory extends Factory
{
    protected $model = UnitKerja::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_unitkerja' => $this->faker->unique()->randomElement([
                'KPH Banyuwangi Raya',
                'KPH Banyuwangi Utara',
                'KPH Banyuwangi Selatan',
                'KPH Banyuwangi Barat',
                'BKPH Kalibaru',
                'BKPH Glenmore',
                'BKPH Genteng',
                'BKPH Rogojampi',
                'BKPH Licin',
                'BKPH Singojuruh',
                'RPH Songgon',
                'RPH Kalipuro',
            ]),
        ];
    }
}
