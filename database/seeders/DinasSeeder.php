<?php

namespace Database\Seeders;

use App\Models\Dinas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DinasSeeder extends Seeder
{
    public function run(): void
    {
        $daftarDinas = [
            'Bappeda',
            'DPMPTSP',
            'Dinas Perdagangan',
            'Dinas Perindustrian',
            'Dinas Koperasi dan UKM',
            'Dinas Pertanian Tanaman Pangan',
            'Dinas Perkebunan dan Peternakan',
            'Dinas Perikanan',
            'Dinas Ketahanan Pangan',
            'Dinas Pariwisata',
            'Dinas Lingkungan Hidup',
            'Badan Pendapatan Daerah',
        ];

        foreach ($daftarDinas as $nama) {
            $slug = Str::slug($nama);
            Dinas::firstOrCreate([
                'kode_dinas' => $slug,
            ], [
                'nama_dinas' => $nama,
                'kepala_dinas' => 'Kepala ' . $nama,
                'alamat' => 'Komplek Perkantoran Pemda Kolaka Utara',
                'jumlah_target_data' => rand(10, 50),
            ]);
        }
    }
}
