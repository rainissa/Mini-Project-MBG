<?php

namespace App\Models;

use App\Observers\LaporanObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([LaporanObserver::class])]
class Laporan extends Model
{
    protected $table = 'laporan';
    protected $fillable = [
        'pelapor_id',
        'distribusi_id',
        'judul',
        'deskripsi',
        'waktu_kejadian',
        'jumlah_terdampak',
        'status',
        'bukti',
        'catatan_pemantau',
    ];
    protected $casts = ['waktu_kejadian' => 'datetime', ];
    
    public function pelapor(): BelongsTo {
        return $this->belongsTo(User::class, 'pelapor_id');
    }
    public function distribusi(): BelongsTo {
        return $this->belongsTo(DistribusiMakanan::class, 'distribusi_id');
    }
    public function penerusan(): HasMany {
        return $this->hasMany(PenerusanLaporan::class, 'laporan_id');
    }
    public function logStatus(): HasMany {
        return $this->hasMany(LogStatusLaporan::class, 'laporan_id');
    }
}
