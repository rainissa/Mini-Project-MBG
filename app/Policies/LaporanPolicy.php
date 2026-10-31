<?php

namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Laporan $laporan): bool
    {
        if ($user->peran === 'pelapor') {
            return $laporan->pelapor_id === $user->id;
        }
        return in_array($user->peran, ['pemantau', 'koordinator'], true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Laporan $laporan): bool
    {
        return $user->peran === 'pelapor'
            && $laporan->pelapor_id === $user->id
            && in_array($laporan->status, ['Dikirim', 'Perlu Dilengkapi'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Laporan $laporan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Laporan $laporan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Laporan $laporan): bool
    {
        return false;
    }
}
