<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        $citas = [
            [
                'fechaCita' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
                'idCuentaTutorado' => 1,
                'canalizacion' => 'Psicologia',
                'resultados' => 'En atención',
                'descripcion' => 'Evaluación inicial sobre estrés académico',
            ],
            [
                'fechaCita' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
                'idCuentaTutorado' => 2,
                'canalizacion' => 'Enfermeria',
                'resultados' => 'En revisión',
                'descripcion' => 'Control de presión arterial y seguimiento de medicamento',
            ],
            [
                'fechaCita' => Carbon::now()->addWeek()->format('Y-m-d H:i:s'),
                'idCuentaTutorado' => 1,
                'canalizacion' => 'Psicologia',
                'resultados' => 'Pendiente',
                'descripcion' => 'Evaluación socioeconómica',
            ],
            [
                'fechaCita' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                'idCuentaTutorado' => 3,
                'canalizacion' => 'Psicologia',
                'resultados' => 'Concluida',
                'descripcion' => 'Orientación vocacional',
            ],
            [
                'fechaCita' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
                'idCuentaTutorado' => 6,
                'canalizacion' => 'Psicologia',
                'resultados' => 'En atención',
                'descripcion' => 'Evaluación inicial sobre estrés académico',
            ],
        ];

        foreach ($citas as $cita) {
            Cita::create($cita);
        }
    }
}

