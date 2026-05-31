<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('calendar_events')) {
            return;
        }
        Schema::table('calendar_events', function (Blueprint $table) {
            if (! Schema::hasColumn('calendar_events', 'color')) {
                $table->string('color', 9)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('calendar_events')) {
            return;
        }
        Schema::table('calendar_events', function (Blueprint $table) {
            if (Schema::hasColumn('calendar_events', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
