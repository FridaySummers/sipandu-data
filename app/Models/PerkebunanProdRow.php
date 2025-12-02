<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkebunanProdRow extends Model
{
    protected $table = 'perkebunan_prod_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

