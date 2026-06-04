<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vida;
use Carbon\Carbon;

class VidaSeeder extends Seeder
{
    public function run(): void
    {
        $vidas = [
            [
                'idCuentaTutorado' => 1,
                'hitoUno' => 'Nacimiento',
                'fechaHitoUno' => '2003-05-12',
                'hitoDos' => 'Ingreso a la primaria',
                'fechaHitoDos' => '2009-08-15',
                'hitoTres' => 'Graduación de secundaria',
                'fechaHitoTres' => '2018-06-30',
                'hitoCuatro' => 'Ingreso a universidad',
                'fechaHitoCuatro' => '2022-09-01',
                'hitoCinco' => 'Primer trabajo de medio tiempo',
                'fechaHitoCinco' => '2024-03-10',
            ],
            [
                'idCuentaTutorado' => 2,
                'hitoUno' => 'Nacimiento',
                'fechaHitoUno' => '2002-11-22',
                'hitoDos' => 'Viaje internacional',
                'fechaHitoDos' => '2015-07-20',
                'hitoTres' => 'Graduación de preparatoria',
                'fechaHitoTres' => '2020-06-30',
                'hitoCuatro' => 'Inicio de carrera universitaria',
                'fechaHitoCuatro' => '2020-09-01',
                'hitoCinco' => 'Servicio social',
                'fechaHitoCinco' => '2023-04-10',
            ],
        ];

        foreach ($vidas as $vida) {
            Vida::create($vida);
        }
    }
}


