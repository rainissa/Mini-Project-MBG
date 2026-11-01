<?php

namespace App\Observers;

use App\Models\Laporan;
use App\Models\LogStatusLaporan;

class LaporanObserver
{
    /**
     * Handle the Laporan "created" event.
     */
    public function created(Laporan $laporan): void
    {
        LogStatusLaporan::create([
            'laporan_id'  => $laporan->id,
            'status_lama' => null,
            'status_baru' => $laporan->status,
            'diubah_oleh' => auth()->id(),
        ]);
    }

    /**
     * Handle the Laporan "updated" event.
     */
    public function updated(Laporan $laporan): void
    {
        if (! $laporan->wasChanged('status')) {
            return;
        }
        LogStatusLaporan::create([
            'laporan_id'  => $laporan->id,
            'status_lama' => $laporan->getOriginal('status'),
            'status_baru' => $laporan->status,
            'diubah_oleh' => auth()->id(),
        ]);
    }

    /**
     * Handle the Laporan "deleted" event.
     */
    public function deleted(Laporan $laporan): void
    {
        //
    }

    /**
     * Handle the Laporan "restored" event.
     */
    public function restored(Laporan $laporan): void
    {
        //
    }

    /**
     * Handle the Laporan "force deleted" event.
     */
    public function forceDeleted(Laporan $laporan): void
    {
        //
    }
}
