<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerikananProduksiRow extends Model
{
    protected $table = 'perikanan_produksi_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

