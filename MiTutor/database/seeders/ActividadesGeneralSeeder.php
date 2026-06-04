<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamenVocacional;
use App\Models\Foda;
use App\Models\Vida;
use App\Models\Lectura;
use Carbon\Carbon;

class ActividadesGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder para examenes_vocacionales usando el modelo ExamenVocacional
        ExamenVocacional::create([
            'areasDeEspecialidad' => 'Tecnología, Ciencias de la Computación, Diseño Gráfico',
            'carrerasRecomendadas' => 'Ingeniería en Sistemas, Licenciatura en Diseño Digital, Ciencia de Datos',
            'recomendaciones' => 'Explorar cursos en línea sobre programación y diseño UX/UI. Participar en proyectos open source.',
            'idCuentaTutorado' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Seeder para foda usando el modelo Foda
        Foda::create([
            'fortaleza' => 'Pensamiento lógico, Capacidad de autoaprendizaje',
            'debilidad' => 'Dificultad para trabajar en equipo grande',
            'oportunidad' => 'Crecimiento del sector tecnológico, Demanda de especialistas en IA',
            'amenazas' => 'Rápida obsolescencia de tecnologías, Alta competencia laboral',
            'idCuentaTutorado' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Seeder para vida usando el modelo Vida
        Vida::create([
            'hitoUno' => 'Graduación de secundaria',
            'fechaHitoUno' => Carbon::parse('2020-07-15'),
            'hitoDos' => 'Inicio de estudios universitarios',
            'fechaHitoDos' => Carbon::parse('2022-08-20'),
            'hitoTres' => 'Primer proyecto personal de programación',
            'fechaHitoTres' => Carbon::parse('2023-03-10'),
            'hitoCuatro' => 'Participación en hackathon',
            'fechaHitoCuatro' => Carbon::parse('2024-11-01'),
            'hitoCinco' => 'Obtención de certificación en desarrollo web',
            'fechaHitoCinco' => Carbon::parse('2025-05-20'),
            'idCuentaTutorado' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Seeder para lectura usando el modelo Lectura
        Lectura::create([
            'nivelComprensionLectora' => 'Avanzado',
            'idCuentaTutorado' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
