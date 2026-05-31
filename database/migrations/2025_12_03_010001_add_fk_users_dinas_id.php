<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('dinas')) {
            return;
        }
        if (! Schema::hasColumn('users', 'dinas_id')) {
            return;
        }

        // Bersihkan referensi tidak valid agar penambahan FK tidak gagal
        try {
            DB::statement('UPDATE users u SET dinas_id = NULL WHERE dinas_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM dinas d WHERE d.id = u.dinas_id)');
        } catch (\Throwable $__) { /* ignore */
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('dinas_id')->references('id')->on('dinas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
        });
    }
};
