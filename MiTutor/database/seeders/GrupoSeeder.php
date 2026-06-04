<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupos = [
            ['idCuentaTutor' => 1, 'letra' => 'A'],
            ['idCuentaTutor' => 2, 'letra' => 'B'],
            ['idCuentaTutor' => 3, 'letra' => 'C'],
        ];

        foreach ($grupos as $grupo) {
            Grupo::create($grupo);
        }
    }
}
