<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('dinas_id')->nullable();
            $table->string('name', 120);
            $table->date('date');
            $table->string('type', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
