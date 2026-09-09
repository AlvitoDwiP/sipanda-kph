<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'KPH Banyuwangi Raya',
            'BKPH Kalibaru',
            'BKPH Glenmore',
            'BKPH Genteng',
            'BKPH Rogojampi',
            'BKPH Licin',
            'BKPH Singojuruh',
            'BKPH Banyuwangi Barat',
            'RPH Songgon',
            'RPH Kalipuro',
        ];

        foreach ($units as $unit) {
            UnitKerja::firstOrCreate(['nama_unitkerja' => $unit]);
        }
    }
}
