<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerindustrianHkRow extends Model
{
    protected $table = 'perindustrian_hk_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

