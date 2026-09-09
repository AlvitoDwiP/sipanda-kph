<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $categories = ['tugas', 'catatan', 'sistem', 'pengumuman'];

        $notifications = [];

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $category = $categories[$i % count($categories)];
            
            $monthsToSub = rand(0, 11);
            $createdAt = now()->subMonths($monthsToSub)->subDays(rand(1, 28));

            $data = [
                'category' => $category,
                'title' => sprintf('Pemberitahuan %s Baru', ucfirst($category)),
                'message' => sprintf('Terdapat pembaruan data untuk kategori %s pada akun Anda.', $category),
                'action_url' => $category === 'tugas' ? '/pegawai/tugas' : '/pegawai/catatan-kegiatan',
            ];

            $notifications[] = [
                'id' => Str::uuid()->toString(),
                'type' => 'App\\Notifications\\ActionableNotification',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode($data),
                'read_at' => (rand(1, 10) <= 6) ? $createdAt->addHours(rand(1, 24)) : null, // 60% read
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('notifications')->insert($notifications);
    }
}
