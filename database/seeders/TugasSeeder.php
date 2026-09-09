<?php

namespace Database\Seeders;

use App\Models\Tugas;
use Illuminate\Database\Seeder;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        Tugas::factory()->count(50)->create();
    }
}
