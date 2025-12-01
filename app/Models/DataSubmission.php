<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSubmission extends Model
{
    protected $fillable = [
        'judul_data',
        'deskripsi',
        'file_path',
        'tahun_perencanaan',
        'user_id',
        'dinas_id',
        'status',
        'catatan_revisi',
    ];

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan dinas
    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    // Scope untuk filtering data berdasarkan role user
    public function scopeByUserRole($query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query;
        }

        if ($user->isSuperAdmin()) {
            // Super Admin bisa lihat semua data dari semua 10 dinas
            return $query;
        }

        if ($user->isAdminDinas()) {
            // Admin Dinas hanya lihat data di dinasnya (salah satu dari 10 dinas)
            return $query->where('dinas_id', $user->dinas_id);
        }

        if ($user->isUser()) {
            // User hanya lihat data miliknya sendiri di dinas yang dipilih
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    // Status helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Get nama dinas (helper untuk view)
    public function getNamaDinasAttribute()
    {
        return $this->dinas ? $this->dinas->nama_dinas : 'Tidak ada dinas';
    }

    // Get nama user (helper untuk view)
    public function getNamaUserAttribute()
    {
        return $this->user ? $this->user->name : 'Tidak ada user';
    }
}