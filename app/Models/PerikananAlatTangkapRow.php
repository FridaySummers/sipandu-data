<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerikananAlatTangkapRow extends Model
{
    protected $table = 'perikanan_alat_tangkap_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

