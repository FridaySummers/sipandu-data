<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkebunanLuasRow extends Model
{
    protected $table = 'perkebunan_luas_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

