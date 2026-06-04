<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamenVocacional;

class ExamenVocacionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $examenes = [
            [
                'areasDeEspecialidad' => 'Tecnología e Ingeniería',
                'carrerasRecomendadas' => 'Ingeniería en Sistemas, Ingeniería Mecatrónica',
                'recomendaciones' => 'Fortalecer habilidades en matemáticas y lógica.',
                'idCuentaTutorado' => 1,
            ],
            [
                'areasDeEspecialidad' => 'Ciencias Sociales y Humanidades',
                'carrerasRecomendadas' => 'Psicología, Trabajo Social',
                'recomendaciones' => 'Participar en actividades de voluntariado y desarrollo humano.',
                'idCuentaTutorado' => 2,
            ],
            [
                'areasDeEspecialidad' => 'Diseño y Arquitectura',
                'carrerasRecomendadas' => 'Arquitectura, Diseño Gráfico',
                'recomendaciones' => 'Explorar herramientas digitales de diseño y dibujo técnico.',
                'idCuentaTutorado' => 3,
            ],
        ];

        foreach ($examenes as $examen) {
            ExamenVocacional::create($examen);
        }
    }
}

