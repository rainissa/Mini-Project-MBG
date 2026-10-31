<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $fillable = ['nama'];
    public function distribusi(): HasMany {
        return $this->hasMany(DistribusiMakanan::class, 'sekolah_id');
    }
}
