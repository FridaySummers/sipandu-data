<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PariwisataWisatawanRow extends Model
{
    protected $table = 'pariwisata_wisatawan_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

