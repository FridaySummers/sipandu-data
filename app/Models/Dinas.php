<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    protected \ = [
        'nama',
        'deskripsi',
        'status',
        'kepala_dinas',
        'email',
        'telepon'
    ];

    public function submissions()
    {
        return \->hasMany(DataSubmission::class);
    }
}
