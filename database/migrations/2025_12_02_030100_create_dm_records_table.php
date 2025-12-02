<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dm_records')) {
            Schema::create('dm_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('submission_id')->nullable()->constrained('data_submissions')->nullOnDelete();
                $table->foreignId('dinas_id')->constrained('dinas')->cascadeOnDelete();
                $table->string('name');
                $table->string('period')->nullable();
                $table->enum('status', ['Pending','In Review','Approved','Rejected'])->default('Approved');
                $table->string('pic')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_records');
    }
};

