<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Asegúrate de importar la fachada Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            ['curp' => 'GALA850615HMCRPN08', 'contrasena' => Hash::make('12345678'), 'rol' => 'Profesor'],
            ['curp' => 'RAMC791210HDFMNR07', 'contrasena' => Hash::make('secret2'), 'rol' => 'Profesor'],
            ['curp' => 'FEGL900322MDFLRL03', 'contrasena' => Hash::make('secret3'), 'rol' => 'Profesor'],
            ['curp' => 'ZUFC030115HDFLRS04', 'contrasena' => Hash::make('12345678'), 'rol' => 'Estudiante'],
            ['curp' => 'MARR021010MDFRYN06', 'contrasena' => Hash::make('secret3'), 'rol' => 'Estudiante'],
            ['curp' => 'HESL030512HDFNRL09', 'contrasena' => Hash::make('12345678'), 'rol' => 'Estudiante'],
        ];

        // --- Generar los 10 estudiantes de "Ingeniería en Sistemas" y "Grupo 1" ---
        // El sufijo '03' para el primer nuevo estudiante (continuando la secuencia lógica de los CURP de ejemplo)
        $curpCounter = 3;
        for ($i = 0; $i < 10; $i++) {
            $curpSuffix = str_pad($curpCounter, 2, '0', STR_PAD_LEFT);
            $usuarios[] = [ // Añadimos al final del arreglo $usuarios existente
                'curp' => 'TEST000000HDFMNR' . $curpSuffix,
                'contrasena' => Hash::make('12345678'),
                'rol' => 'Estudiante',
            ];
            $curpCounter++; // Incrementa para el siguiente CURP
        }

        // --- Generar los 10 estudiantes variados ---
        // Continuamos la secuencia de sufijos numéricos para los CURP
        for ($i = 0; $i < 10; $i++) {
            $curpSuffix = str_pad($curpCounter, 2, '0', STR_PAD_LEFT);
            $usuarios[] = [ // Añadimos al final del arreglo $usuarios existente
                'curp' => 'TEST000000HDFMNR' . $curpSuffix,
                'contrasena' => Hash::make('12345678'),
                'rol' => 'Estudiante',
            ];
            $curpCounter++; // Incrementa para el siguiente CURP
        }

        // Itera sobre el arreglo combinado y crea o actualiza los usuarios
        foreach ($usuarios as $usuario) {
            User::firstOrCreate(
                ['curp' => $usuario['curp']], // Busca por CURP
                $usuario // Si no existe, crea con todos los datos
            );
        }
    }
}
