<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiRow extends Model
{
    protected $table = 'koperasi_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

