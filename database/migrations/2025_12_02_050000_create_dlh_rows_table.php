<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dlh_rows')) {
            Schema::create('dlh_rows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dinas_id')->constrained('dinas')->cascadeOnDelete();
                $table->string('uraian');
                $table->string('satuan')->nullable();
                $table->string('y2019')->nullable();
                $table->string('y2020')->nullable();
                $table->string('y2021')->nullable();
                $table->string('y2022')->nullable();
                $table->string('y2023')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dlh_rows');
    }
};

