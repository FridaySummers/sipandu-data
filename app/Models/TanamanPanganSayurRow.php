<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanamanPanganSayurRow extends Model
{
    protected $table = 'tanaman_pangan_sayur_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

