<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    protected $table = 'peran';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const PERAN_ADMIN = 'Admin';
    const PERAN_STAF = 'Staf';
    const PERAN_KADES = 'Kades';

    const PERAN_ADMIN_ID = 1;
    const PERAN_STAF_ID = 2;
    const PERAN_KADES_ID = 3;
}
