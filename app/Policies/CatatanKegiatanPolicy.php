<?php

namespace App\Policies;

use App\Models\CatatanKegiatan;
use App\Models\User;

class CatatanKegiatanPolicy
{
    /**
     * Pegawai can view their own CatatanKegiatan.
     * Admin and KPH can view all CatatanKegiatan.
     */
    public function view(User $user, CatatanKegiatan $catatanKegiatan): bool
    {
        if (in_array($user->role, ['admin', 'kph'])) {
            return true;
        }

        return $user->pegawai && $user->pegawai->id === $catatanKegiatan->pegawai_id;
    }

    /**
     * Pegawai can update their own CatatanKegiatan.
     * Admin and KPH can update (e.g. setujui, revisi) all CatatanKegiatan.
     */
    public function update(User $user, CatatanKegiatan $catatanKegiatan): bool
    {
        if (in_array($user->role, ['admin', 'kph'])) {
            return true;
        }

        return $user->pegawai && $user->pegawai->id === $catatanKegiatan->pegawai_id;
    }

    /**
     * Pegawai can delete their own CatatanKegiatan.
     * Admin and KPH cannot delete CatatanKegiatan.
     */
    public function delete(User $user, CatatanKegiatan $catatanKegiatan): bool
    {
        if (in_array($user->role, ['admin', 'kph'])) {
            return false; // Admin/KPH shouldn't delete catatans.
        }

        return $user->pegawai && $user->pegawai->id === $catatanKegiatan->pegawai_id;
    }
}
