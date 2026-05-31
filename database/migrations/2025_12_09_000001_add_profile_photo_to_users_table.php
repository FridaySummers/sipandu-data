<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'profile_photo_path')) {
                    $table->string('profile_photo_path')->nullable()->after('dinas_id');
                }
            });
        } catch (\Throwable $__) {
        }
    }

    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'profile_photo_path')) {
                    $table->dropColumn('profile_photo_path');
                }
            });
        } catch (\Throwable $__) {
        }
    }
};

