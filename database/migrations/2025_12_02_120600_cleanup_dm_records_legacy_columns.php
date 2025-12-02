<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dm_records')) { return; }
        Schema::table('dm_records', function (Blueprint $table) {
            if (Schema::hasColumn('dm_records', 'opd')) {
                $table->dropColumn('opd');
            }
            if (Schema::hasColumn('dm_records', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('dm_records', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('dm_records', 'files')) {
                $table->dropColumn('files');
            }
            if (Schema::hasColumn('dm_records', 'schema')) {
                $table->dropColumn('schema');
            }
        });
    }

    public function down(): void
    {
        // No rollback for legacy cleanup
    }
};

