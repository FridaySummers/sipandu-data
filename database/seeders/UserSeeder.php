<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin (Bappeda)
        User::create([
            'name' => 'Super Admin Bappeda',
            'email' => 'admin.bappeda@kolakautara.go.id',
            'password' => Hash::make('sipandu2025'),
            'role' => 'super_admin',
            'dinas_id' => null,
        ]);

        // Admin untuk setiap dinas (10 dinas)
        $dinas = Dinas::all();
        
        $adminData = [
            ['dinas' => 'DPMPTSP', 'email' => 'admin.dpmptsp@kolakautara.go.id'],
            ['dinas' => 'Perdagangan', 'email' => 'admin.perdagangan@kolakautara.go.id'],
            ['dinas' => 'Perindustrian', 'email' => 'admin.perindustrian@kolakautara.go.id'],
            ['dinas' => 'Koperasi', 'email' => 'admin.koperasi@kolakautara.go.id'],
            ['dinas' => 'Tanaman Pangan', 'email' => 'admin.tanamanpangan@kolakautara.go.id'],
            ['dinas' => 'Perkebunan', 'email' => 'admin.perkebunan@kolakautara.go.id'],
            ['dinas' => 'Perikanan', 'email' => 'admin.perikanan@kolakautara.go.id'],
            ['dinas' => 'Ketahanan Pangan', 'email' => 'admin.ketapang@kolakautara.go.id'],
            ['dinas' => 'Pariwisata', 'email' => 'admin.pariwisata@kolakautara.go.id'],
            ['dinas' => 'DLH', 'email' => 'admin.dlh@kolakautara.go.id'],
        ];

        foreach ($adminData as $data) {
            $dinasModel = $dinas->where('nama_dinas', $data['dinas'])->first();
            
            if ($dinasModel) {
                User::create([
                    'name' => 'Admin ' . $data['dinas'],
                    'email' => $data['email'],
                    'password' => Hash::make('dinas123'),
                    'role' => 'admin_dinas',
                    'dinas_id' => $dinasModel->id,
                ]);
            }
        }

        // User untuk setiap dinas (10 user, satu untuk setiap dinas)
        $userData = [
            ['dinas' => 'DPMPTSP', 'email' => 'user.dpmptsp@example.com'],
            ['dinas' => 'Perdagangan', 'email' => 'user.perdagangan@example.com'],
            ['dinas' => 'Perindustrian', 'email' => 'user.perindustrian@example.com'],
            ['dinas' => 'Koperasi', 'email' => 'user.koperasi@example.com'],
            ['dinas' => 'Tanaman Pangan', 'email' => 'user.tanamanpangan@example.com'],
            ['dinas' => 'Perkebunan', 'email' => 'user.perkebunan@example.com'],
            ['dinas' => 'Perikanan', 'email' => 'user.perikanan@example.com'],
            ['dinas' => 'Ketahanan Pangan', 'email' => 'user.ketapang@example.com'],
            ['dinas' => 'Pariwisata', 'email' => 'user.pariwisata@example.com'],
            ['dinas' => 'DLH', 'email' => 'user.dlh@example.com'],
        ];

        foreach ($userData as $data) {
            $dinasModel = $dinas->where('nama_dinas', $data['dinas'])->first();
            
            if ($dinasModel) {
                User::create([
                    'name' => 'User ' . $data['dinas'],
                    'email' => $data['email'],
                    'password' => Hash::make('user123'),
                    'role' => 'user',
                    'dinas_id' => $dinasModel->id,
                ]);
            }
        }
    }
}