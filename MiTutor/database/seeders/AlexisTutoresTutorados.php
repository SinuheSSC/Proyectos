<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\Tutor;
use App\Models\Tutorado;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlexisTutoresTutorados extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i=0; $i < 10; $i++) {
            User::create([
                'curp' => 'XXXX00000' . $i,
                'contrasena' => Hash::make('12345678') ,
                'rol' => 'Profesor'
            ]);

            Tutor::create([
                'nombres' => fake()->firstName(),
                'apellidoPaterno'=> fake()->lastName(),
                'apellidoMaterno' => fake()->lastName(),
                'fechaNacimiento' => fake()->date(),
                'curp' => 'XXXX00000' . $i,
                'RFC' => 'RFC000' . $i,
                'genero' => fake()->randomElement(['M','F']),
                'cedulaProfesional' => '0000',
                'especialidad' => 'Example',
                'titulo' => 'Example',
                'telefono' => '44555'.$i,
                'estadoCivil' => 'Soltero/a',
                'email' => fake()->email(),
                'municipio' => 'Example',
                'estado' => 'Example',
                'calleYnumero' => 'Example',
                'codigoPostal' => '00000',
                'fotoPerfil' => 'ruta/' . $i
            ]);
        }

        Grupo::create([
            'idCuentaTutor' => 1,
            'letra' => 'A'
        ]);

        for ($i=10; $i < 20; $i++) {
            User::create([
                'curp' => 'XXXX00000' . $i,
                'contrasena' => Hash::make('12345678') ,
                'rol' => 'Estudiante'
            ]);

            Tutorado::create([
                'nombres' => fake()->firstName(),
                'apellidoPaterno'=> fake()->lastName(),
                'apellidoMaterno' => fake()->lastName(),
                'fechaNacimiento' => fake()->date(),
                'curp' => 'XXXX00000' . $i,
                'genero' => fake()->randomElement(['M','F']),
                'idGrupo' => 1,
                'canalizacion' => fake()->randomElement(['Enfermeria', 'Psicologia']),
                'carrera' => 'Example',
                'razonesCarrera' => 'Example',
                'telefono' => '44555'.$i,
                'estadoCivil' => 'Soltero/a',
                'email' => fake()->email(),
                'municipio' => 'Example',
                'estado' => 'Example',
                'calleYnumero' => 'Example',
                'codigoPostal' => '00000',
                'discapacidadFisica' => 'Example',
                'enfemerdad' => 'Example',
                'situacionPsicologica' => 'Example',
                'necesidadEspecial' => 'Example',
                'fotoPerfil' => 'ruta/' . $i
            ]);
        }



        /*User::create([
            'curp' => 'XXXX000001',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000002',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000003',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000004',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000005',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000006',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000007',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000008',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000009',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);

        User::create([
            'curp' => 'XXXX000010',
            'contrasena' => Hash::make('12345678') ,
            'rol' => 'P'
        ]);*/


    }
}
