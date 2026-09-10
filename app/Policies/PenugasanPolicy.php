<?php

namespace App\Policies;

use App\Models\Penugasan;
use App\Models\User;

class PenugasanPolicy
{
    /**
     * Pegawai can view their own Penugasan.
     * Admin and KPH can view all Penugasan.
     */
    public function view(User $user, Penugasan $penugasan): bool
    {
        if (in_array($user->role, ['admin', 'kph'])) {
            return true;
        }

        return $user->pegawai && $user->pegawai->id === $penugasan->pegawai_id;
    }

    /**
     * Pegawai can update their own Penugasan (e.g. changing status to mulai).
     * Admin and KPH can update (e.g. setujui, revisi) all Penugasan.
     */
    public function update(User $user, Penugasan $penugasan): bool
    {
        if (in_array($user->role, ['admin', 'kph'])) {
            return true;
        }

        return $user->pegawai && $user->pegawai->id === $penugasan->pegawai_id;
    }
}
