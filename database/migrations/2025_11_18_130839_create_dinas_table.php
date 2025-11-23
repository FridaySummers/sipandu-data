<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dinas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dinas'); // Contoh: Dinas Perdagangan
            $table->string('kode_dinas')->unique(); // Contoh: perdagangan (untuk slug URL)
            $table->string('kepala_dinas')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->integer('jumlah_target_data')->default(0); // Untuk KPI di Dashboard
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinas');
    }
};
