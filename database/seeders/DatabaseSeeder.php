<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DinasSeeder::class,
        ]);
        if (env('ENABLE_DEMO_USER', false)) {
            $this->call([
                UserSeeder::class,
            ]);
        }
    }
}
