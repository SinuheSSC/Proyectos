<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lectura;

class LecturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturas = [
            [
                'nivelComprensionLectora' => 'Alto',
                'idCuentaTutorado' => 1,
            ],
            [
                'nivelComprensionLectora' => 'Medio',
                'idCuentaTutorado' => 2,
            ],
            [
                'nivelComprensionLectora' => 'Bajo',
                'idCuentaTutorado' => 3,
            ],
        ];

        foreach ($lecturas as $lectura) {
            Lectura::create($lectura);
        }
    }
}

