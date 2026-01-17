<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'nip' => '0000000000',
            'role' => 'admin',
            'status_akun' => 'aktif',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12341234'),
        ]);

        User::create([
            'name' => 'KPH',
            'nip' => '1111111111',
            'role' => 'kph',
            'status_akun' => 'nonaktif',
            'email' => 'kph@gmail.com',
            'password' => Hash::make('12341234'),
        ]);

        User::create([
            'name' => 'Pegawai',
            'nip' => '2222222222',
            'role' => 'pegawai',
            'status_akun' => 'nonaktif',
            'email' => 'pegawai@gmail.com',
            'password' => Hash::make('12341234'),
        ]); 
    }
}
