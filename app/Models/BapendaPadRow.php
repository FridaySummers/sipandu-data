<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BapendaPadRow extends Model
{
    protected $table = 'bapenda_pad_rows';

    protected $guarded = [];

    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
