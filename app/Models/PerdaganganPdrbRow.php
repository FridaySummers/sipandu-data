<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerdaganganPdrbRow extends Model
{
    protected $table = 'perdagangan_pdrb_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

