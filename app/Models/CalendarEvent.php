<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $connection = 'mysql';

    protected $table = 'calendar_events';

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
