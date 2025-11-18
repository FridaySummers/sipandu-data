<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected \ = [
        'title',
        'content',
        'user_id',
        'category',
        'status'
    ];

    public function user()
    {
        return \->belongsTo(User::class);
    }
}
