<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerikananInfrastrukturRow extends Model
{
    protected $table = 'perikanan_infrastruktur_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

