<?php

namespace Database\Factories;

use App\Models\DataDiri;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->boolean(90) ? 'aktif' : 'nonaktif';
        $qrGeneratedAt = $this->faker->dateTimeBetween('-12 months', '-6 months');

        return [
            'user_id' => User::factory(),
            'unitkerja_id' => UnitKerja::factory(),
            'golongan_id' => Golongan::factory(),
            'jabatan_id' => Jabatan::factory(),
            'status_pegawai' => $status,
            'data_diri_id' => DataDiri::factory(),
            'qr_token' => Pegawai::generateUniqueQrToken(),
            'qr_generated_at' => $qrGeneratedAt,
            'qr_regenerated_at' => null,
        ];
    }

    /**
     * Configure the factory relations.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Pegawai $pegawai) {
            if ($pegawai->user) {
                $pegawai->user->status_akun = $pegawai->status_pegawai;
                $pegawai->user->role = 'pegawai';
            }
        })->afterCreating(function (Pegawai $pegawai) {
            if ($pegawai->user) {
                $pegawai->user->update([
                    'status_akun' => $pegawai->status_pegawai,
                    'role' => 'pegawai',
                ]);
            }
        });
    }
}
