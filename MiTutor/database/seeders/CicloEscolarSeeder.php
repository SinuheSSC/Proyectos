<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CicloEscolar;
use App\Models\Tutor;
use Carbon\Carbon;

class CicloEscolarSeeder extends Seeder
{
    public function run(): void
    {
        $tutores = Tutor::all();

        if ($tutores->isEmpty()) {
            $this->command->warn('No hay tutores disponibles para asignar ciclos escolares.');
            return;
        }

        foreach ($tutores as $tutor) {
            CicloEscolar::create([
                'ciclo' => 'Agosto-Diciembre',
                'year' => Carbon::now()->year,
                'sesiones' => 16,
                'idCuentaTutor' => $tutor->idCuentaTutor,
            ]);

            CicloEscolar::create([
                'ciclo' => 'Enero-Junio',
                'year' => Carbon::now()->year,
                'sesiones' => 16,
                'idCuentaTutor' => $tutor->idCuentaTutor,
            ]);
        }

        $this->command->info('Ciclos escolares generados correctamente.');
    }
}
