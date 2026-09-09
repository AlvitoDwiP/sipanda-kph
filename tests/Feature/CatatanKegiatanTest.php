<?php

namespace Tests\Feature;

use App\Models\CatatanKegiatan;
use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatatanKegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_pegawai_can_create_catatan_kegiatan_via_tugas()
    {
        Storage::fake('public');

        $pegawai = Pegawai::factory()->create();
        $user = $pegawai->user;
        $penugasan = Penugasan::factory()->create(['pegawai_id' => $pegawai->id]);

        $response = $this->actingAs($user)->post(route('pegawai.tugas.catatan.store', $penugasan->id), [
            'tanggal_kegiatan' => now()->format('Y-m-d'),
            'deskripsi' => 'Deskripsi kegiatan',
            'hasil_kegiatan' => 'Hasil dari lapangan sangat baik.',
            'kendala' => 'Tidak ada kendala',
            'foto_kegiatan' => [UploadedFile::fake()->image('dokumentasi.jpg')],
        ]);

        $response->assertRedirect(route('pegawai.tugas.show', $penugasan->tugas_id));
        $this->assertDatabaseHas('catatan_kegiatan', [
            'pegawai_id' => $pegawai->id,
            'status_verifikasi' => 'menunggu_verifikasi',
            'status' => 'ajukan',
        ]);
    }

    public function test_admin_can_approve_catatan_kegiatan()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $catatan = CatatanKegiatan::factory()->create([
            'status_verifikasi' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.catatan_kegiatan.setujui', $catatan->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('catatan_kegiatan', [
            'id' => $catatan->id,
            'status_verifikasi' => 'disetujui',
        ]);
    }

    public function test_kph_can_reject_catatan_kegiatan()
    {
        $kph = User::factory()->create(['role' => 'kph']);
        $catatan = CatatanKegiatan::factory()->create([
            'status_verifikasi' => 'menunggu_verifikasi',
        ]);

        // KPH rejects by sending 'revisi' / 'tolak'. Let's assume the route name is tolak.
        $response = $this->actingAs($kph)->post(route('kph.catatan_kegiatan.tolak', $catatan->id), [
            'catatan_verifikasi' => 'Kegiatan tidak sesuai, ulangi dokumentasi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('catatan_kegiatan', [
            'id' => $catatan->id,
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => 'Kegiatan tidak sesuai, ulangi dokumentasi.',
        ]);
    }
}
