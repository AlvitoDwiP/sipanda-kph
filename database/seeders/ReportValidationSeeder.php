<?php

namespace Database\Seeders;

use Database\Factories\ReportValidationFactory;
use Illuminate\Database\Seeder;

class ReportValidationSeeder extends Seeder
{
    public function run(): void
    {
        ReportValidationFactory::new()->count(50)->create();
    }
}
