<?php

namespace App\Console\Commands;

use App\Models\Penugasan;
use App\Models\User;
use App\Services\ActionableNotificationService;
use Illuminate\Console\Command;

class CheckDeadlineNotifications extends Command
{
    protected $signature = 'sipanda:check-deadline-notifications';

    protected $description = 'Generate actionable deadline notifications for SIPANDA-KPH';

    public function handle(ActionableNotificationService $notificationService): int
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $soonTasks = Penugasan::with(['tugas', 'pegawai.user'])
            ->whereHas('tugas', fn ($q) => $q->whereDate('deadline', $tomorrow))
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->get();

        foreach ($soonTasks as $task) {
            if ($task->pegawai?->user) {
                $notificationService->notifyUser(
                    $task->pegawai->user,
                    'tugas_deadline',
                    'Deadline tugas mendekat',
                    'Tugas "'.($task->tugas->judul ?? '-').'" akan jatuh tempo besok.',
                    route('pegawai.tugas.show', $task->tugas_id),
                    ['penugasan_id' => $task->id, 'tugas_id' => $task->tugas_id],
                    'deadline_soon:'.$task->id.':'.$tomorrow
                );
            }
        }

        $lateTasks = Penugasan::with(['tugas', 'pegawai.user'])
            ->whereHas('tugas', fn ($q) => $q->whereDate('deadline', '<', $today))
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->get();

        foreach ($lateTasks as $task) {
            if ($task->pegawai?->user) {
                $notificationService->notifyUser(
                    $task->pegawai->user,
                    'tugas_terlambat',
                    'Tugas terlambat',
                    'Tugas "'.($task->tugas->judul ?? '-').'" sudah melewati deadline.',
                    route('pegawai.tugas.show', $task->tugas_id),
                    ['penugasan_id' => $task->id, 'tugas_id' => $task->tugas_id],
                    'deadline_late:'.$task->id.':'.$today
                );
            }
        }

        $adminsAndKph = User::query()->whereIn('role', ['admin', 'kph'])->where('status_akun', 'aktif')->get();

        foreach ($adminsAndKph as $user) {
            $countNeedUpdate = Penugasan::query()
                ->whereHas('tugas', fn ($q) => $q->whereDate('tanggal_tugas', $today))
                ->whereNotIn('status', ['selesai', 'dibatalkan'])
                ->where(function ($q) {
                    $q->where('status', 'belum_dikerjakan')
                        ->orWhere('progres_persen', 0)
                        ->orWhereNull('progres_updated_at');
                })
                ->count();

            if ($countNeedUpdate > 0) {
                $prefix = $user->role === 'admin' ? 'admin' : 'kph';
                $notificationService->notifyUser(
                    $user,
                    'progres_belum_update',
                    'Ada pegawai belum update progres',
                    'Ditemukan '.$countNeedUpdate.' penugasan hari ini yang belum diperbarui progresnya.',
                    route($prefix.'.dashboard'),
                    ['jumlah' => $countNeedUpdate],
                    'progress_missing:'.$user->id.':'.$today
                );
            }
        }

        $this->info('Deadline notifications checked and generated.');

        return self::SUCCESS;
    }
}
