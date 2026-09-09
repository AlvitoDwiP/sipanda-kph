<?php

namespace Database\Seeders;

use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use Illuminate\Database\Seeder;

class CatatanKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $penugasans = Penugasan::with('tugas')->get();
        $pegawais = Pegawai::all();

        // 1. Seed 35 CatatanKegiatan linked to a Penugasan
        $linkedCount = min(35, $penugasans->count());
        $shuffledPenugasans = $penugasans->shuffle();

        for ($i = 0; $i < $linkedCount; $i++) {
            $penugasan = $shuffledPenugasans[$i];

            CatatanKegiatan::factory()->create([
                'pegawai_id' => $penugasan->pegawai_id,
                'penugasan_id' => $penugasan->id,
                'tanggal_kegiatan' => $penugasan->created_at->format('Y-m-d'),
                'periode_bulan' => (int) $penugasan->created_at->format('m'),
                'periode_tahun' => (int) $penugasan->created_at->format('Y'),
            ]);
        }

        // 2. Seed 15 independent CatatanKegiatan
        $independentCount = 50 - $linkedCount;
        for ($i = 0; $i < $independentCount; $i++) {
            $pegawai = $pegawais->random();

            CatatanKegiatan::factory()->create([
                'pegawai_id' => $pegawai->id,
                'penugasan_id' => null,
            ]);
        }
    }
}
