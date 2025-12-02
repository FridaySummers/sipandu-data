<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerindustrianGrowthRow extends Model
{
    protected $table = 'perindustrian_growth_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

