<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSubmission extends Model
{
    protected $table = 'data_submissions';
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
