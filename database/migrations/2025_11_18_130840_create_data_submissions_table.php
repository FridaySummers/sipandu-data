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
        Schema::create('data_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dinas_id')->constrained('dinas')->onDelete('cascade');
            $table->string('judul_data');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('tahun_perencanaan', 4);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_submissions');
    }
};
