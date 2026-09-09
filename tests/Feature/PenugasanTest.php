<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PenugasanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_penugasan()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $pegawai = Pegawai::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.penugasan.store'), [
            'judul' => 'Tugas Test',
            'deskripsi' => 'Deskripsi tugas test',
            'tanggal_tugas' => now()->format('Y-m-d'),
            'deadline' => now()->addDays(2)->format('Y-m-d'),
            'prioritas' => 'sedang',
            'pegawai_id' => [$pegawai->id],
            'template' => UploadedFile::fake()->create('template.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.penugasan.index'));
        $this->assertDatabaseHas('tugas', [
            'judul' => 'Tugas Test',
        ]);
        $this->assertDatabaseHas('penugasan', [
            'pegawai_id' => $pegawai->id,
            'status' => 'belum_dikerjakan',
        ]);
    }

    public function test_kph_can_approve_penugasan()
    {
        $kph = User::factory()->create(['role' => 'kph']);
        
        $tugas = Tugas::factory()->create();
        $penugasan = Penugasan::factory()->create([
            'tugas_id' => $tugas->id,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($kph)->post(route('kph.penugasan.setujui', $penugasan->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('penugasan', [
            'id' => $penugasan->id,
            'status' => 'selesai',
        ]);
    }
}
