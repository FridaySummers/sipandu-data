<?php

namespace Database\Factories;

use App\Models\Dinas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Dinas>
 */
class DinasFactory extends Factory
{
    protected $model = Dinas::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
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
        ]);

        return [
            'nama_dinas' => $name,
            'kode_dinas' => Str::slug($name),
            'kepala_dinas' => 'Kepala '.$name,
            'alamat' => 'Komplek Perkantoran Pemda Kolaka Utara',
            'telepon' => fake()->phoneNumber(),
            'jumlah_target_data' => fake()->numberBetween(10, 50),
        ];
    }
}
