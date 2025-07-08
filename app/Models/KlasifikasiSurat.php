<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KlasifikasiSurat extends Model
{
    protected $table = 'klasifikasi_surat';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class);
    }
}
