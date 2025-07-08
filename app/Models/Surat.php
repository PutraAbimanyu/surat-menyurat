<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    protected $table = 'surat';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const SURAT_MASUK = 'Surat Masuk';
    const SURAT_KELUAR = 'Surat Keluar';
    const SURAT_DISPOSISI = 'Surat Disposisi';

    public function klasifikasiSurat(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiSurat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
