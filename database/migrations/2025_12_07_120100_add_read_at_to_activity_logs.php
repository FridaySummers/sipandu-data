<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }
        Schema::table('activity_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_logs', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }
};
