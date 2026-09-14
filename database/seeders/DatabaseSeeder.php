<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Hanya berisi data referensi yang dibutuhkan dan 3 akun awal:
     * 1 Admin, 1 KPH, 1 Pegawai.
     * Semua data demo/test (tugas, catatan, log, dll) tidak disertakan.
     */
    public function run(): void
    {
        $this->call([
            UnitKerjaSeeder::class,
            GolonganSeeder::class,
            JabatanSeeder::class,
            UserSeeder::class,
        ]);
    }
}
