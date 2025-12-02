<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'position',
        'dinas_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    // Role helper methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdminDinas()
    {
        return $this->role === 'admin_dinas';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Scope untuk filtering data berdasarkan role
    public function scopeByRole($query)
    {
        $user = auth()->user();
        
        if ($user->isAdminDinas()) {
            return $query->where('dinas_id', $user->dinas_id);
        }
        
        if ($user->isUser()) {
            return $query->where('id', $user->id);
        }
        
        return $query; // Super Admin bisa lihat semua
    }
}