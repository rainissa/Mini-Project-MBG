<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LaporanWorkflow
{
    // BR-03: peta perpindahan status yang diizinkan.
    // Format: [status asal][status tujuan] => peran yang boleh
    private const ALUR = [
        'Dikirim' => [
            'Ditinjau'         => 'pemantau',
            'Perlu Dilengkapi' => 'pemantau',
        ],
        'Perlu Dilengkapi' => [
            'Dikirim' => 'pelapor',
        ],
        'Ditinjau' => [
            'Perlu Dilengkapi'          => 'pemantau',
            'Ditandai untuk Diteruskan' => 'pemantau',
            'Selesai'                   => 'koordinator',
        ],
        'Ditandai untuk Diteruskan' => [
            'Telah Diteruskan' => 'koordinator',
        ],
        'Telah Diteruskan' => [
            'Selesai' => 'koordinator',
        ],
        'Selesai' => [],
    ];

    public function bolehPindah(Laporan $laporan, string $tujuan, User $user): bool
    {
        $peranYangBoleh = self::ALUR[$laporan->status][$tujuan] ?? null;

        if ($peranYangBoleh !== $user->peran) {
            return false;
        }

        // Pelapor hanya boleh mengirim ulang laporan miliknya
        if ($user->peran === 'pelapor' && $laporan->pelapor_id !== $user->id) {
            return false;
        }

        return true;
    }

    public function pindahkan(Laporan $laporan, string $tujuan, User $user, ?string $catatan = null): void
    {
        if (! $this->bolehPindah($laporan, $tujuan, $user)) {
            throw ValidationException::withMessages([
                'status' => "Perubahan status dari \"{$laporan->status}\" ke \"{$tujuan}\" tidak diperbolehkan.",
            ]);
        }

        $laporan->status = $tujuan;

        if ($catatan !== null) {
            $laporan->catatan_pemantau = $catatan;
        }

        $laporan->save();
    }
}