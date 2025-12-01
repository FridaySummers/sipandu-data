<?php

namespace Database\Seeders;

use App\Models\Dinas;
use Illuminate\Database\Seeder;

class DinasSeeder extends Seeder
{
    public function run()
    {
        $dinas = [
            ['nama_dinas' => 'DPMPTSP', 'kode_dinas' => 'DPM-PTSP'],
            ['nama_dinas' => 'Perdagangan', 'kode_dinas' => 'DAGANG'],
            ['nama_dinas' => 'Perindustrian', 'kode_dinas' => 'INDUSTRI'],
            ['nama_dinas' => 'Koperasi', 'kode_dinas' => 'KOPERASI'],
            ['nama_dinas' => 'Tanaman Pangan', 'kode_dinas' => 'TANPANG'],
            ['nama_dinas' => 'Perkebunan', 'kode_dinas' => 'KEBUN'],
            ['nama_dinas' => 'Perikanan', 'kode_dinas' => 'PERIKANAN'],
            ['nama_dinas' => 'Ketahanan Pangan', 'kode_dinas' => 'KETAPANG'],
            ['nama_dinas' => 'Pariwisata', 'kode_dinas' => 'PARIWISATA'],
            ['nama_dinas' => 'DLH', 'kode_dinas' => 'DLH'],
        ];

        foreach ($dinas as $data) {
            Dinas::create($data);
        }
    }
}