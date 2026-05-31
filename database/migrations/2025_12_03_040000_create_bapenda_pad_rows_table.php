<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bapenda_pad_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dinas_id')->nullable();
            $table->string('uraian');
            $table->string('satuan')->nullable();
            $table->json('values')->nullable();
            $table->string('y2025')->nullable();
            $table->string('y2026')->nullable();
            $table->string('y2027')->nullable();
            $table->string('y2028')->nullable();
            $table->string('y2029')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bapenda_pad_rows');
    }
};
