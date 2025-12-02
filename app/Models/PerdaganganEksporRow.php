<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerdaganganEksporRow extends Model
{
    protected $table = 'perdagangan_ekspor_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

