<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE dm_records MODIFY COLUMN status ENUM('Pending','In Review','Approved','Rejected','Menunggu Persetujuan','Disetujui','Ditolak') NOT NULL DEFAULT 'Pending'");
        DB::statement("UPDATE dm_records SET status = 'Menunggu Persetujuan' WHERE status IN ('Pending','In Review')");
        DB::statement("UPDATE dm_records SET status = 'Disetujui' WHERE status = 'Approved'");
        DB::statement("UPDATE dm_records SET status = 'Ditolak' WHERE status = 'Rejected'");
        DB::statement("ALTER TABLE dm_records MODIFY COLUMN status ENUM('Menunggu Persetujuan','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu Persetujuan'");

        DB::statement("ALTER TABLE data_submissions MODIFY COLUMN status ENUM('pending','approved','rejected','Menunggu Persetujuan','Disetujui','Ditolak') NOT NULL DEFAULT 'pending'");
        DB::statement("UPDATE data_submissions SET status = 'Menunggu Persetujuan' WHERE status IN ('pending')");
        DB::statement("UPDATE data_submissions SET status = 'Disetujui' WHERE status = 'approved'");
        DB::statement("UPDATE data_submissions SET status = 'Ditolak' WHERE status = 'rejected'");
        DB::statement("ALTER TABLE data_submissions MODIFY COLUMN status ENUM('Menunggu Persetujuan','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu Persetujuan'");
    }

    public function down(): void
    {
        DB::statement("UPDATE dm_records SET status = 'Approved' WHERE status = 'Disetujui'");
        DB::statement("UPDATE dm_records SET status = 'Rejected' WHERE status = 'Ditolak'");
        DB::statement("UPDATE dm_records SET status = 'Pending' WHERE status = 'Menunggu Persetujuan'");
        DB::statement("ALTER TABLE dm_records MODIFY COLUMN status ENUM('Pending','In Review','Approved','Rejected') NOT NULL DEFAULT 'Approved'");

        DB::statement("UPDATE data_submissions SET status = 'approved' WHERE status = 'Disetujui'");
        DB::statement("UPDATE data_submissions SET status = 'rejected' WHERE status = 'Ditolak'");
        DB::statement("UPDATE data_submissions SET status = 'pending' WHERE status = 'Menunggu Persetujuan'");
        DB::statement("ALTER TABLE data_submissions MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
};
