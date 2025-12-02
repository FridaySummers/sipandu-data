<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerikananBinaKelompokRow extends Model
{
    protected $table = 'perikanan_bina_kelompok_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

