<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ketahanan_pangan_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dinas_id')->nullable();
            $table->string('uraian');
            $table->string('satuan')->nullable();
            $table->json('values')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ketahanan_pangan_rows');
    }
};

