<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetahananPanganRow extends Model
{
    protected $table = 'ketahanan_pangan_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

