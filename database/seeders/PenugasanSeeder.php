<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\PenugasanStatusHistory;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenugasanSeeder extends Seeder
{
    public function run(): void
    {
        $pegawais = Pegawai::all();
        $tugasList = Tugas::all();

        $adminOrKph = User::whereIn('role', ['admin', 'kph'])->get();

        // Create 50 unique Penugasan
        for ($i = 0; $i < 50; $i++) {
            $pegawai = $pegawais->random();
            $tugas = $tugasList->random();

            // Prevent duplicate compound key (pegawai_id, tugas_id) if it exists
            $exists = Penugasan::where('pegawai_id', $pegawai->id)
                ->where('tugas_id', $tugas->id)
                ->exists();

            if ($exists) {
                $i--;

                continue;
            }

            $penugasan = Penugasan::factory()->create([
                'pegawai_id' => $pegawai->id,
                'tugas_id' => $tugas->id,
            ]);

            // Seed 1-3 PenugasanStatusHistory entries for this Penugasan
            $this->seedStatusHistory($penugasan, $adminOrKph);
        }
    }

    private function seedStatusHistory(Penugasan $penugasan, $users): void
    {
        $status = $penugasan->status;
        $user = $users->random();
        $baseDate = $penugasan->created_at;

        if ($status === 'belum_dikerjakan') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);
        } elseif ($status === 'sedang_dikerjakan') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'belum_dikerjakan',
                'status_sesudah' => 'sedang_dikerjakan',
                'catatan' => 'Pegawai mulai mengerjakan tugas.',
                'created_at' => $baseDate->addHours(4),
                'updated_at' => $baseDate->addHours(4),
            ]);
        } elseif ($status === 'menunggu_verifikasi') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'belum_dikerjakan',
                'status_sesudah' => 'sedang_dikerjakan',
                'catatan' => 'Pegawai mulai mengerjakan tugas.',
                'created_at' => $baseDate->addHours(2),
                'updated_at' => $baseDate->addHours(2),
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'sedang_dikerjakan',
                'status_sesudah' => 'menunggu_verifikasi',
                'catatan' => 'Pegawai mengunggah laporan hasil pekerjaan.',
                'created_at' => $baseDate->addDays(3),
                'updated_at' => $baseDate->addDays(3),
            ]);
        } elseif ($status === 'selesai') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'belum_dikerjakan',
                'status_sesudah' => 'sedang_dikerjakan',
                'catatan' => 'Pegawai mulai mengerjakan tugas.',
                'created_at' => $baseDate->addHours(2),
                'updated_at' => $baseDate->addHours(2),
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'sedang_dikerjakan',
                'status_sesudah' => 'menunggu_verifikasi',
                'catatan' => 'Pegawai mengunggah laporan hasil pengerjaan.',
                'created_at' => $baseDate->addDays(2),
                'updated_at' => $baseDate->addDays(2),
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => 'menunggu_verifikasi',
                'status_sesudah' => 'selesai',
                'catatan' => 'Pekerjaan selesai diverifikasi dan disetujui.',
                'created_at' => $baseDate->addDays(4),
                'updated_at' => $baseDate->addDays(4),
            ]);
        } elseif ($status === 'revisi') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'belum_dikerjakan',
                'status_sesudah' => 'sedang_dikerjakan',
                'catatan' => 'Pegawai mulai mengerjakan tugas.',
                'created_at' => $baseDate->addHours(2),
                'updated_at' => $baseDate->addHours(2),
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $penugasan->pegawai->user_id,
                'status_sebelum' => 'sedang_dikerjakan',
                'status_sesudah' => 'menunggu_verifikasi',
                'catatan' => 'Pegawai mengunggah laporan hasil pengerjaan.',
                'created_at' => $baseDate->addDays(2),
                'updated_at' => $baseDate->addDays(2),
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => 'menunggu_verifikasi',
                'status_sesudah' => 'revisi',
                'catatan' => 'Format pelaporan kurang lengkap, perlu direvisi.',
                'created_at' => $baseDate->addDays(3),
                'updated_at' => $baseDate->addDays(3),
            ]);
        } elseif ($status === 'dibatalkan') {
            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'belum_dikerjakan',
                'catatan' => 'Penugasan baru diterbitkan.',
                'created_at' => $baseDate,
                'updated_at' => $baseDate,
            ]);

            PenugasanStatusHistory::create([
                'penugasan_id' => $penugasan->id,
                'user_id' => $user->id,
                'status_sebelum' => 'belum_dikerjakan',
                'status_sesudah' => 'dibatalkan',
                'catatan' => 'Penugasan dibatalkan karena dialihkan.',
                'created_at' => $baseDate->addDays(1),
                'updated_at' => $baseDate->addDays(1),
            ]);
        }
    }
}
