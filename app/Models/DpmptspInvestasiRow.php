<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpmptspInvestasiRow extends Model
{
    protected $table = 'dpmptsp_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

