<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Foda;

class FodaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $analisis = [
            [
                'fortaleza' => 'Buena capacidad de análisis y resolución de problemas',
                'debilidad' => 'Dificultad para hablar en público',
                'oportunidad' => 'Acceso a becas de estudio en el extranjero',
                'amenazas' => 'Falta de oportunidades laborales en su localidad',
                'idCuentaTutorado' => 1, // Asegúrate que este ID exista
            ],
            [
                'fortaleza' => 'Organización y puntualidad',
                'debilidad' => 'Falta de experiencia práctica',
                'oportunidad' => 'Programas de voluntariado universitario',
                'amenazas' => 'Problemas económicos en su familia',
                'idCuentaTutorado' => 2,
            ],
            [
                'fortaleza' => 'Gran creatividad y liderazgo',
                'debilidad' => 'Tendencia a procrastinar',
                'oportunidad' => 'Red de networking con egresados',
                'amenazas' => 'Altas exigencias académicas',
                'idCuentaTutorado' => 3,
            ],
        ];

        foreach ($analisis as $item) {
            Foda::create($item);
        }
    }
}

