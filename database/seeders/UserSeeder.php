<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (Bappeda)
        // Kita asumsikan Bappeda adalah dinas pertama atau id tertentu, 
        // tapi untuk super admin biasanya dinas_id bisa null atau set ke Bappeda.
        $bappeda = Dinas::where('kode_dinas', 'bappeda')->first();

        User::firstOrCreate([
            'email' => 'admin.bappeda@kolakautara.go.id',
        ], [
            'name' => 'Admin Bappeda',
            'password' => Hash::make('sipandu2025'),
            'role' => 'super_admin',
            'position' => 'Administrator Utama',
            'dinas_id' => $bappeda ? $bappeda->id : null,
        ]);

        // 2. Admin Dinas untuk seluruh dinas
        Dinas::all()->each(function ($dinas) {
            if ($dinas->kode_dinas === 'bappeda') {
                return; // Hindari bentrok email dengan super admin
            }

            $slug = $dinas->kode_dinas;
            $emailPart = Str::startsWith($slug, 'dinas-') ? Str::replaceFirst('dinas-', '', $slug) : $slug;
            $email = 'admin.' . $emailPart . '@kolakautara.go.id';

            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => 'Admin ' . $dinas->nama_dinas,
                'password' => Hash::make('dinas123'),
                'role' => 'admin_dinas',
                'position' => 'Operator Dinas',
                'dinas_id' => $dinas->id,
            ]);
        });

        // 3. User Dinas untuk seluruh dinas (termasuk Bappeda)
        Dinas::all()->each(function ($dinas) {
            $slug = $dinas->kode_dinas;
            $emailPart = Str::startsWith($slug, 'dinas-') ? Str::replaceFirst('dinas-', '', $slug) : $slug;
            $email = 'user.' . $emailPart . '@kolakautara.go.id';

            User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => 'User ' . $dinas->nama_dinas,
                'password' => Hash::make('user123'),
                'role' => 'user',
                'position' => 'Staf Dinas',
                'dinas_id' => $dinas->id,
            ]);
        });

        // 4. User Demo (Umum)
        User::firstOrCreate([
            'email' => 'user.demo@example.com',
        ], [
            'name' => 'User Demo',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'position' => 'Staf Fungsional',
            'dinas_id' => null,
        ]);
    }
}
