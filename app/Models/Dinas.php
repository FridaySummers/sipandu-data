<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dinas extends Model
{
    protected $table = 'dinas';
    protected $fillable = [
        'nama_dinas',
        'kode_dinas',
        'kepala_dinas',
        'alamat',
        'telepon',
        'jumlah_target_data',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(DataSubmission::class);
    }
}
