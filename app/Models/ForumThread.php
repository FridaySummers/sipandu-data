<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    protected $table = 'forum_threads';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'likes' => 'integer',
        'views' => 'integer',
    ];

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
