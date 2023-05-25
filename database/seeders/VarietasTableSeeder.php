<?php

namespace Database\Seeders;

use App\Models\Varietas;
use Illuminate\Database\Seeder;

class VarietasTableSeeder extends Seeder
{
    public function run()
    {
        $varietas = [
            [
                'id'             => 1,
                'name_varietas'           => 'Jangkiriah Adro',
            ],
            [
                'id'             => 2,
                'name_varietas'           => 'Lumbu Hijau',
            ],
            [
                'id'             => 3,
                'name_varietas'           => 'Lumbu Kuning',
            ],
            [
                'id'             => 4,
                'name_varietas'           => 'Lumbu Putih',
            ],
            [
                'id'             => 5,
                'name_varietas'           => 'Sangga Sembalun',
            ],
            [
                'id'             => 6,
                'name_varietas'           => 'Tawangmangu Baru',
            ],
        ];

        Varietas::insert($varietas);
    }
}
