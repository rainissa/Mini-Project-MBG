<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStatusLaporan extends Model
{
    protected $table = 'log_status_laporan';
    public $timestamp = false;
    protected $fillable = [
        'laporan_id',
        'status_lama',
        'status_baru',
        'diubah_oleh',
    ];

    public function laporan(): BelongsTo {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
    public function pengubah(): BelongsTo {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
