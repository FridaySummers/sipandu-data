<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (Bappeda)
        // Kita asumsikan Bappeda adalah dinas pertama atau id tertentu, 
        // tapi untuk super admin biasanya dinas_id bisa null atau set ke Bappeda.
        $bappeda = Dinas::where('kode_dinas', 'bappeda')->first();

        User::create([
            'name' => 'Admin Bappeda',
            'email' => 'admin.bappeda@kolakautara.go.id', // Username di login form mungkin pakai email
            'password' => Hash::make('sipandu2025'),
            'role' => 'super_admin',
            'position' => 'Administrator Utama',
            'dinas_id' => $bappeda ? $bappeda->id : null,
        ]);

        // 2. Admin Dinas (Contoh: Perdagangan)
        $dinasPerdagangan = Dinas::where('kode_dinas', 'dinas-perdagangan')->first();
        
        if ($dinasPerdagangan) {
            User::create([
                'name' => 'Admin Perdagangan',
                'email' => 'admin.perdagangan@kolakautara.go.id',
                'password' => Hash::make('dinas123'),
                'role' => 'admin_dinas',
                'position' => 'Operator Dinas',
                'dinas_id' => $dinasPerdagangan->id,
            ]);
        }

        // 3. User Demo (Umum)
        User::create([
            'name' => 'User Demo',
            'email' => 'user.demo@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'position' => 'Staf Fungsional',
            'dinas_id' => null,
        ]);
    }
}