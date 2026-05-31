<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('forum_replies') && ! Schema::hasColumn('forum_replies', 'pinned')) {
            Schema::table('forum_replies', function (Blueprint $table) {
                $table->boolean('pinned')->default(false)->after('content');
                $table->index('pinned');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('forum_replies') && Schema::hasColumn('forum_replies', 'pinned')) {
            Schema::table('forum_replies', function (Blueprint $table) {
                $table->dropIndex(['pinned']);
                $table->dropColumn('pinned');
            });
        }
    }
};
