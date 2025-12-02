<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PariwisataPemanduRow extends Model
{
    protected $table = 'pariwisata_pemandu_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

