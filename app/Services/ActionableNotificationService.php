<?php

namespace App\Services;

use App\Models\Penugasan;
use App\Models\User;
use App\Notifications\ActionableNotification;
use Illuminate\Notifications\DatabaseNotification;

class ActionableNotificationService
{
    public function notifyUser(User $user, string $category, string $title, string $message, ?string $actionUrl = null, array $extra = [], ?string $uniqueKey = null): void
    {
        if ($uniqueKey && $this->hasRecentNotification($user, $uniqueKey)) {
            return;
        }

        $payload = $extra;
        if ($uniqueKey) {
            $payload['unique_key'] = $uniqueKey;
        }

        $user->notify(new ActionableNotification($category, $title, $message, $actionUrl, $payload));
    }

    public function notifyRoles(array $roles, string $category, string $title, string $message, ?string $actionUrl = null, array $extra = [], ?string $uniqueKey = null): void
    {
        $users = User::query()
            ->whereIn('role', $roles)
            ->where('status_akun', 'aktif')
            ->get();

        foreach ($users as $user) {
            $this->notifyUser($user, $category, $title, $message, $actionUrl, $extra, $uniqueKey ? $uniqueKey . ':user:' . $user->id : null);
        }
    }

    public function notifyTaskAssigned(Penugasan $penugasan, string $routePrefix): void
    {
        $penugasan->loadMissing('tugas', 'pegawai.user');
        $user = $penugasan->pegawai?->user;
        if (!$user) {
            return;
        }

        $this->notifyUser(
            $user,
            'tugas_baru',
            'Tugas baru ditugaskan',
            'Anda menerima tugas baru: ' . ($penugasan->tugas->judul ?? '-'),
            route('pegawai.tugas.show', $penugasan->tugas_id),
            [
                'penugasan_id' => $penugasan->id,
                'tugas_id' => $penugasan->tugas_id,
                'route_prefix' => $routePrefix,
            ],
            'task_assigned:' . $penugasan->id
        );
    }

    private function hasRecentNotification(User $user, string $uniqueKey): bool
    {
        return DatabaseNotification::query()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->where('data->unique_key', $uniqueKey)
            ->where('created_at', '>=', now()->subDay())
            ->exists();
    }
}
