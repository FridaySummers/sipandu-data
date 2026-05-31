<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dlh_rows')) {
            Schema::table('dlh_rows', function (Blueprint $table) {
                foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                    if (! Schema::hasColumn('dlh_rows', $col)) {
                        $table->string($col)->nullable()->after('y2023');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dlh_rows')) {
            Schema::table('dlh_rows', function (Blueprint $table) {
                foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                    if (Schema::hasColumn('dlh_rows', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
