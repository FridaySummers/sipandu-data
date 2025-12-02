<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DlhRow extends Model
{
    protected $table = 'dlh_rows';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }
}

