<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dpmptsp_rows')) {
            Schema::table('dpmptsp_rows', function (Blueprint $table) {
                foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                    if (! Schema::hasColumn('dpmptsp_rows', $col)) {
                        $table->string($col)->nullable()->after('satuan');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dpmptsp_rows')) {
            Schema::table('dpmptsp_rows', function (Blueprint $table) {
                foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                    if (Schema::hasColumn('dpmptsp_rows', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
