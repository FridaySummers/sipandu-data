<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PariwisataAkomodasiRow extends Model
{
    protected $table = 'pariwisata_akomodasi_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

