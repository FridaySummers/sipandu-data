<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'role', 'dinas_id', 'action', 'entity', 'record_id', 'approval_status', 'metadata',
    ];

    protected $casts = ['metadata' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }
}
