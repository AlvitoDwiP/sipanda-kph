<?php

namespace Database\Seeders;

use Database\Factories\DisplayScanLogFactory;
use Illuminate\Database\Seeder;

class DisplayScanLogSeeder extends Seeder
{
    public function run(): void
    {
        DisplayScanLogFactory::new()->count(50)->create();
    }
}
