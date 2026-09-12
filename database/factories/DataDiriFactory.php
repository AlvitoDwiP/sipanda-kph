<?php

namespace Database\Factories;

use App\Models\DataDiri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataDiri>
 */
class DataDiriFactory extends Factory
{
    protected $model = DataDiri::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerId = \Faker\Factory::create('id_ID');

        $cities = [
            'Surabaya',
            'Sidoarjo',
            'Gresik',
            'Lamongan',
            'Mojokerto',
            'Pasuruan',
            'Malang',
            'Jember',
            'Banyuwangi',
            'Kediri',
        ];

        $city = $this->faker->randomElement($cities);
        $gender = $this->faker->randomElement(['L', 'P']);

        return [
            'no_hp' => '0812'.$this->faker->numerify('########'),
            'alamat' => sprintf(
                'Jl. %s No. %d, RT %02d/RW %02d, %s',
                $fakerId->streetName(),
                $this->faker->numberBetween(1, 150),
                $this->faker->numberBetween(1, 12),
                $this->faker->numberBetween(1, 12),
                $city
            ),
            'tempat_lahir' => $city,
            'tgl_lahir' => $this->faker->dateTimeBetween('-55 years', '-22 years')->format('Y-m-d'),
            'jenis_kelamin' => $gender,
            'foto' => null,
            'kartu_identitas' => $this->faker->unique()->numerify('35################'), // 16 digit Indonesian NIK starting with East Java (35)
        ];
    }
}
