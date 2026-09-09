<?php

namespace Database\Seeders;

use App\Models\Golongan;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    public function run(): void
    {
        $golongans = [
            'Pembina Utama Madya (IV/d)',
            'Pembina Utama Muda (IV/c)',
            'Pembina Tingkat I (IV/b)',
            'Pembina (IV/a)',
            'Penata Tingkat I (III/d)',
            'Penata (III/c)',
            'Penata Muda Tingkat I (III/b)',
            'Penata Muda (III/a)',
            'Pengatur Tingkat I (II/d)',
            'Pengatur (II/c)',
        ];

        foreach ($golongans as $gol) {
            Golongan::firstOrCreate(['nama_golongan' => $gol]);
        }
    }
}
