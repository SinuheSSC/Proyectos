<?php

namespace Database\Seeders;

use App\Models\ExamenVocacional;
use App\Models\Lectura;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\GrupoSeeder;
use Database\Seeders\TutorSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TutoradoSeeder;
use Database\Seeders\ActividadesGeneralSeeder;
use Database\Seeders\EstudianteSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AlexisTutoresTutorados::class);
        //$this->call(AlexisCitas::class);
        // User::factory(10)->create();

        /*User::factory()->create([
            'curp' => 'XXXXXXXXXX',
            'contrasena' => 'XXX',
            'rol' => 'A'
        ]);*/

        $this->call([
        UserSeeder::class,
        TutorSeeder::class,
        GrupoSeeder::class,
        TutoradoSeeder::class,
        CitaSeeder::class,
        CicloEscolarSeeder::class,
        FodaSeeder::class,
        VidaSeeder::class,
        LecturaSeeder::class,
        ExamenVocacionalSeeder::class,
        EstudianteSeeder::class,

    ]);
    }
}
