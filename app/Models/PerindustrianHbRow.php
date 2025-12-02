<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerindustrianHbRow extends Model
{
    protected $table = 'perindustrian_hb_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

