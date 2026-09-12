<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UnitKerjaSeeder::class,
            GolonganSeeder::class,
            JabatanSeeder::class,
            UserSeeder::class,
            TugasSeeder::class,
            PenugasanSeeder::class,
            CatatanKegiatanSeeder::class,
            ReportValidationSeeder::class,
            LogSeeder::class,
            DisplayScanLogSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
