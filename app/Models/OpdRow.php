<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpdRow extends Model
{
    protected $table = 'opd_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }
}

