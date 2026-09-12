<?php

namespace Database\Seeders;

use App\Models\DataDiri;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Demo password used for all seeded accounts.
     * Using updateOrCreate ensures re-seeding always enforces this password.
     */
    private const DEMO_PASSWORD = 'password123';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hashedPassword = Hash::make(self::DEMO_PASSWORD);

        // ── 1. Administrators ───────────────────────────────────────────────
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => sprintf('admin%d@sipanda.test', $i)],
                [
                    'name' => sprintf('Administrator %d', $i),
                    'nip' => sprintf('19850101201001100%d', $i),
                    'role' => 'admin',
                    'status_akun' => 'aktif',
                    'password' => $hashedPassword,
                ]
            );
        }

        // Legacy admin (kept for backward compatibility)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Legacy',
                'nip' => '0000000000',
                'role' => 'admin',
                'status_akun' => 'aktif',
                'password' => $hashedPassword,
            ]
        );

        // ── 2. KPH Koordinator ──────────────────────────────────────────────
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => sprintf('kph%d@sipanda.test', $i)],
                [
                    'name' => sprintf('KPH Koordinator %d', $i),
                    'nip' => sprintf('19800202200802100%d', $i),
                    'role' => 'kph',
                    'status_akun' => 'aktif',
                    'password' => $hashedPassword,
                ]
            );
        }

        // Legacy kph (kept for backward compatibility)
        User::updateOrCreate(
            ['email' => 'kph@gmail.com'],
            [
                'name' => 'KPH Legacy',
                'nip' => '1111111111',
                'role' => 'kph',
                'status_akun' => 'aktif',
                'password' => $hashedPassword,
            ]
        );

        // ── 3. Pegawai (50 users) ───────────────────────────────────────────
        for ($i = 1; $i <= 50; $i++) {
            $email = sprintf('pegawai%02d@sipanda.test', $i);
            // 90% aktif, 10% nonaktif
            $statusPegawai = ($i <= 45) ? 'aktif' : 'nonaktif';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => sprintf('Pegawai Kedinasan %02d', $i),
                    'nip' => sprintf('199003032015031%03d', $i),
                    'role' => 'pegawai',
                    'status_akun' => $statusPegawai,
                    'password' => $hashedPassword,
                ]
            );

            // Create Pegawai profile relations only if they don't exist yet
            if (! $user->pegawai) {
                $dataDiri = DataDiri::factory()->create([
                    'kartu_identitas' => sprintf('357800000000%04d', $i),
                ]);

                Pegawai::factory()->create([
                    'user_id' => $user->id,
                    'data_diri_id' => $dataDiri->id,
                    'status_pegawai' => $statusPegawai,
                    'unitkerja_id' => UnitKerja::inRandomOrder()->first()->id,
                    'golongan_id' => Golongan::inRandomOrder()->first()->id,
                    'jabatan_id' => Jabatan::inRandomOrder()->first()->id,
                ]);
            }
        }

        // Legacy pegawai (kept for backward compatibility)
        $legacyPegawaiUser = User::updateOrCreate(
            ['email' => 'pegawai@gmail.com'],
            [
                'name' => 'Pegawai Legacy',
                'nip' => '2222222222',
                'role' => 'pegawai',
                'status_akun' => 'aktif',
                'password' => $hashedPassword,
            ]
        );

        if (! $legacyPegawaiUser->pegawai) {
            $dataDiri = DataDiri::factory()->create([
                'kartu_identitas' => '3578000000009999',
            ]);

            Pegawai::factory()->create([
                'user_id' => $legacyPegawaiUser->id,
                'data_diri_id' => $dataDiri->id,
                'status_pegawai' => 'aktif',
                'unitkerja_id' => UnitKerja::inRandomOrder()->first()->id,
                'golongan_id' => Golongan::inRandomOrder()->first()->id,
                'jabatan_id' => Jabatan::inRandomOrder()->first()->id,
            ]);
        }
    }
}
