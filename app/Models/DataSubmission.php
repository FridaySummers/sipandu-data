<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSubmission extends Model
{
    protected \ = [
        'title',
        'description',
        'dinas_id',
        'data',
        'status',
        'submitted_by',
        'submitted_at'
    ];

    protected \ = [
        'data' => 'array',
        'submitted_at' => 'datetime'
    ];

    public function dinas()
    {
        return \->belongsTo(Dinas::class);
    }
}
