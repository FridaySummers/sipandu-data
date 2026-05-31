<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('role', 40)->index();
            $table->unsignedBigInteger('dinas_id')->nullable()->index();
            $table->string('action', 40)->index();
            $table->string('entity', 120)->nullable();
            $table->unsignedBigInteger('record_id')->nullable()->index();
            $table->string('approval_status', 20)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
