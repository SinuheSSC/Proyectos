<?php

namespace Database\Seeders;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlexisCitas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 10; $i++) {
                Cita::create([
                'fechaCita' => Carbon::create(2025,random_int(5,7),rand(1,30)),
                'idCuentaTutorado' => random_int(1,30),
                'descripcion' => 'Example',
                'canalizacion' => fake()->randomElement(['Psicologia','Enfermeria']),
            ]);
        }


    }
}
