<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PariwisataObjekJenisRow extends Model
{
    protected $table = 'pariwisata_objek_jenis_rows';
    protected $guarded = [];
    protected $casts = [
        'values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

