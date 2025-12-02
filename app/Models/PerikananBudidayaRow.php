<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerikananBudidayaRow extends Model
{
    protected $table = 'perikanan_budidaya_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

