<?php

namespace Database\Seeders;

use App\Models\DataDiri;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Password default untuk semua akun awal.
     * Wajib diganti setelah login pertama kali di production.
     */
    private const DEFAULT_PASSWORD = 'password123';

    public function run(): void
    {
        $password = Hash::make(self::DEFAULT_PASSWORD);

        // ── 1. Admin ─────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@sipanda.id'],
            [
                'name'        => 'Administrator',
                'nip'         => '198501012010011001',
                'role'        => 'admin',
                'status_akun' => 'aktif',
                'password'    => $password,
            ]
        );

        // ── 2. KPH Koordinator ───────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'kph@sipanda.id'],
            [
                'name'        => 'KPH Koordinator',
                'nip'         => '198002022008021001',
                'role'        => 'kph',
                'status_akun' => 'aktif',
                'password'    => $password,
            ]
        );

        // ── 3. Pegawai ───────────────────────────────────────────────────────
        $pegawaiUser = User::updateOrCreate(
            ['email' => 'pegawai@sipanda.id'],
            [
                'name'        => 'Pegawai Contoh',
                'nip'         => '199003032015031001',
                'role'        => 'pegawai',
                'status_akun' => 'aktif',
                'password'    => $password,
            ]
        );

        // Buat profil pegawai jika belum ada
        if (! $pegawaiUser->pegawai) {
            $dataDiri = DataDiri::create([
                'jenis_kelamin'  => 'L',
                'tempat_lahir'   => 'Banyuwangi',
                'tgl_lahir'      => '1990-03-03',
                'kartu_identitas' => '3578000000000001',
                'alamat'         => 'Jl. Contoh No. 1, Banyuwangi',
                'no_hp'          => '081234567890',
            ]);

            Pegawai::create([
                'user_id'        => $pegawaiUser->id,
                'data_diri_id'   => $dataDiri->id,
                'status_pegawai' => 'aktif',
                'unitkerja_id'   => UnitKerja::first()->id,
                'golongan_id'    => Golongan::first()->id,
                'jabatan_id'     => Jabatan::first()->id,
            ]);
        }
    }
}
