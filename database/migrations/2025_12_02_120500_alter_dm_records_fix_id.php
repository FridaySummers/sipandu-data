<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('dm_records')) {
            return;
        }

        try {
            $driver = config('database.default');
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE dm_records MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            }
        } catch (\Throwable $__) {
            // ignore
        }

        Schema::table('dm_records', function (Blueprint $table) {
            if (!Schema::hasColumn('dm_records', 'submission_id')) {
                $table->foreignId('submission_id')->nullable()->constrained('data_submissions')->nullOnDelete();
            }
            if (!Schema::hasColumn('dm_records', 'dinas_id')) {
                $table->foreignId('dinas_id')->nullable()->constrained('dinas')->nullOnDelete();
            }
            if (!Schema::hasColumn('dm_records', 'name')) {
                $table->string('name')->default('');
            }
            if (!Schema::hasColumn('dm_records', 'period')) {
                $table->string('period')->nullable();
            }
            if (!Schema::hasColumn('dm_records', 'status')) {
                $table->enum('status', ['Pending','In Review','Approved','Rejected'])->default('Approved');
            }
            if (!Schema::hasColumn('dm_records', 'pic')) {
                $table->string('pic')->nullable();
            }
            if (!Schema::hasColumn('dm_records', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('dm_records', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // No-op: schema repair migration
    }
};

