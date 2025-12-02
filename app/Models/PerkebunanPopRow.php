<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkebunanPopRow extends Model
{
    protected $table = 'perkebunan_pop_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

