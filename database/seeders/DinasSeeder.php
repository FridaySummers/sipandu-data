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
            
            Dinas::create([
                'nama_dinas' => $nama,
                'kode_dinas' => Str::slug($nama),
                'kepala_dinas' => 'Kepala ' . $nama, // Data dummy
                'alamat' => 'Komplek Perkantoran Pemda Kolaka Utara',
                'jumlah_target_data' => rand(10, 50),
            ]);
        }
    }
}