<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DmRecord extends Model
{
    protected $table = 'dm_records';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function submission()
    {
        return $this->belongsTo(DataSubmission::class, 'submission_id');
    }
}

