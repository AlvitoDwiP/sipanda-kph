<?php

namespace App\Services;

use App\Models\Penugasan;
use App\Models\PenugasanStatusHistory;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenugasanService
{
    protected ActionableNotificationService $notificationService;

    public function __construct(ActionableNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function createPenugasan(array $data, array $pegawaiIds, ?\Illuminate\Http\UploadedFile $template, string $routePrefix)
    {
        return DB::transaction(function () use ($data, $pegawaiIds, $template, $routePrefix) {
            if ($template) {
                $data['template'] = $template->store('template_tugas', 'public');
            }

            $tugas = Tugas::create($data);

            foreach ($pegawaiIds as $pegawaiId) {
                $penugasan = Penugasan::create([
                    'pegawai_id' => $pegawaiId,
                    'tugas_id' => $tugas->id,
                    'status' => 'belum_dikerjakan',
                ]);
                $this->notificationService->notifyTaskAssigned($penugasan, $routePrefix);
            }

            return $tugas;
        });
    }

    public function updatePenugasan(Tugas $penugasan, array $data, array $pegawaiIds, ?\Illuminate\Http\UploadedFile $template)
    {
        return DB::transaction(function () use ($penugasan, $data, $pegawaiIds, $template) {
            if ($template) {
                // Hapus template lama
                if ($penugasan->template && Storage::disk('public')->exists($penugasan->template)) {
                    Storage::disk('public')->delete($penugasan->template);
                }

                // Simpan template baru
                $data['template'] = $template->store('template_tugas', 'public');
            }

            $penugasan->update($data);

            Penugasan::where('tugas_id', $penugasan->id)
                ->whereNotIn('pegawai_id', $pegawaiIds)
                ->delete();

            foreach ($pegawaiIds as $pegawaiId) {
                Penugasan::firstOrCreate(
                    [
                        'tugas_id' => $penugasan->id,
                        'pegawai_id' => $pegawaiId,
                    ],
                    ['status' => 'belum_dikerjakan']
                );
            }

            return $penugasan;
        });
    }

    public function deletePenugasan(Tugas $penugasan)
    {
        return DB::transaction(function () use ($penugasan) {
            Penugasan::where('tugas_id', $penugasan->id)->delete();
            $penugasan->delete();
        });
    }

    public function setujuiPenugasan(Penugasan $penugasan, string $roleName)
    {
        if ($penugasan->status !== 'menunggu_verifikasi') {
            throw new \Exception('Status tugas tidak valid untuk disetujui.');
        }

        $this->changeStatus($penugasan, 'selesai', "Tugas disetujui {$roleName}.");
        $penugasan->loadMissing('pegawai.user', 'tugas');
        if ($penugasan->pegawai?->user) {
            $this->notificationService->notifyUser(
                $penugasan->pegawai->user,
                'tugas_verifikasi',
                'Tugas disetujui',
                'Tugas "'.($penugasan->tugas->judul ?? '-').'" telah disetujui.',
                route('pegawai.tugas.show', $penugasan->tugas_id),
                ['penugasan_id' => $penugasan->id]
            );
        }

        return $penugasan;
    }

    public function revisiPenugasan(Penugasan $penugasan, string $catatanRevisi)
    {
        if ($penugasan->status !== 'menunggu_verifikasi') {
            throw new \Exception('Status tugas tidak valid untuk revisi.');
        }

        $penugasan->update([
            'catatan_revisi' => $catatanRevisi,
        ]);

        $this->changeStatus($penugasan, 'revisi', $catatanRevisi);
        $penugasan->loadMissing('pegawai.user', 'tugas');
        if ($penugasan->pegawai?->user) {
            $this->notificationService->notifyUser(
                $penugasan->pegawai->user,
                'tugas_verifikasi',
                'Tugas perlu revisi',
                'Tugas "'.($penugasan->tugas->judul ?? '-').'" diminta revisi.',
                route('pegawai.tugas.show', $penugasan->tugas_id),
                ['penugasan_id' => $penugasan->id]
            );
        }

        return $penugasan;
    }

    public function batalkanPenugasan(Penugasan $penugasan, string $alasanPembatalan)
    {
        if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
            throw new \Exception('Tugas ini tidak bisa dibatalkan.');
        }

        $penugasan->update([
            'alasan_pembatalan' => $alasanPembatalan,
        ]);

        $this->changeStatus($penugasan, 'dibatalkan', $alasanPembatalan);

        return $penugasan;
    }

    public function changeStatus(Penugasan $penugasan, string $nextStatus, ?string $catatan = null): void
    {
        $statusSebelum = $penugasan->status;
        $payload = ['status' => $nextStatus];

        if ($nextStatus === 'selesai') {
            $payload['selesai_at'] = now();
        }

        $penugasan->update($payload);

        PenugasanStatusHistory::create([
            'penugasan_id' => $penugasan->id,
            'user_id' => \Auth::id(),
            'status_sebelum' => $statusSebelum,
            'status_sesudah' => $nextStatus,
            'catatan' => $catatan,
        ]);
    }
}
