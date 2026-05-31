<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->unsignedInteger('likes')->default(0)->after('content');
            $table->unsignedInteger('views')->default(0)->after('likes');
        });
    }

    public function down(): void
    {
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['likes', 'views']);
        });
    }
};
