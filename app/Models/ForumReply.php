<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    protected $table = 'forum_replies';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'pinned' => 'boolean',
    ];
}
