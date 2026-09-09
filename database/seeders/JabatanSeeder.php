<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            'Kepala Sub Seksi (KSS)',
            'KTU (Kepala Tata Usaha)',
            'KBKPH (Kepala Bagian Kesatuan Pemangkuan Hutan)',
            'KRPH (Kepala Resort Pemangkuan Hutan)',
            'Staf Perencanaan KPH',
            'Staf Produksi & Ekowisata',
            'Staf PSDH & Kehutanan',
            'Staf Pelaporan & Sistem',
            'Polisi Hutan (Polhut)',
            'Penyuluh Kehutanan',
        ];

        foreach ($jabatans as $jab) {
            Jabatan::firstOrCreate(['nama_jabatan' => $jab]);
        }
    }
}
