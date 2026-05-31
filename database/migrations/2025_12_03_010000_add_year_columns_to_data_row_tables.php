<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables2019 = [
            'dpmptsp_rows',
            'koperasi_rows',
            'perindustrian_hb_rows',
            'perindustrian_hk_rows',
            'perindustrian_growth_rows',
            'perkebunan_pop_rows',
            'perkebunan_prod_rows',
            'perkebunan_luas_rows',
            'perdagangan_ekspor_rows',
            'dlh_rows',
        ];
        foreach ($tables2019 as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    foreach (['y2019', 'y2020', 'y2021', 'y2022', 'y2023'] as $col) {
                        if (! Schema::hasColumn($t, $col)) {
                            $table->string($col)->nullable()->after('satuan');
                        }
                    }
                });
            }
        }

        $tables2025 = [
            'perdagangan_pdrb_rows',
        ];
        foreach ($tables2025 as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                        if (! Schema::hasColumn($t, $col)) {
                            $table->string($col)->nullable()->after('satuan');
                        }
                    }
                });
            }
        }
    }

    public function down(): void
    {
        $tables2019 = [
            'dpmptsp_rows',
            'koperasi_rows',
            'perindustrian_hb_rows',
            'perindustrian_hk_rows',
            'perindustrian_growth_rows',
            'perkebunan_pop_rows',
            'perkebunan_prod_rows',
            'perkebunan_luas_rows',
            'perdagangan_ekspor_rows',
            'dlh_rows',
        ];
        foreach ($tables2019 as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    foreach (['y2019', 'y2020', 'y2021', 'y2022', 'y2023'] as $col) {
                        if (Schema::hasColumn($t, $col)) {
                            $table->dropColumn($col);
                        }
                    }
                });
            }
        }

        $tables2025 = ['perdagangan_pdrb_rows'];
        foreach ($tables2025 as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    foreach (['y2025', 'y2026', 'y2027', 'y2028', 'y2029'] as $col) {
                        if (Schema::hasColumn($t, $col)) {
                            $table->dropColumn($col);
                        }
                    }
                });
            }
        }
    }
};
