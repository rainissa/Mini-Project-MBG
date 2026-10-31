<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerusanLaporan extends Model
{
    protected $table = 'penerusan_laporan';
    protected $fillable = [
        'laporan_id',
        'jenis_laporan',
        'nama_penerima',
        'catatan_penerusan',
        'dicatat_oleh',
        'waktu_diteruskan',
    ];
    protected $casts = ['waktu_diteruskan'=> 'datetime', ];

    public function laporan(): BelongsTo {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
    public function pencatat(): BelongsTo {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
