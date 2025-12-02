<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('opd_rows')) {
            Schema::create('opd_rows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dinas_id')->constrained('dinas')->cascadeOnDelete();
                $table->string('table_key');
                $table->string('uraian');
                $table->string('satuan')->nullable();
                $table->json('values')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('opd_rows');
    }
};

