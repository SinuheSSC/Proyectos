<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tutorado;
use Illuminate\Support\Facades\Hash;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estudiantes = [
            [
                'curp' => 'TEST000000HDFMNR00',
                'contrasena' => Hash::make('12345678'),
                'rol' => 'Estudiante',
                'nombres' => 'Sinuhé',
                'apellidoPaterno' => 'Sánchez',
                'apellidoMaterno' => 'Contreras',
                'fechaNacimiento' => '2003-09-27',
                'genero' => 'M',
                'idGrupo' => 1,
                'canalizacion' => false,
                'carrera' => 'Ingeniería en Sistemas',
                'razonesCarrera' => 'Me encanta la tecnología',
                'telefono' => '4434115956',
                'estadoCivil' => 'Soltero/a',
                'email' => 'Sinuhe@example.com',
                'municipio' => 'Morelia',
                'estado' => 'Michoacán',
                'calleYnumero' => 'Av. Siempre Viva #123',
                'codigoPostal' => '09870',
            ],

            [
                'curp' => 'TEST000000HDFMNR01',
                'contrasena' => Hash::make('12345678'),
                'rol' => 'Estudiante',
                'nombres' => 'Juan',
                'apellidoPaterno' => 'Pérez',
                'apellidoMaterno' => 'García',
                'fechaNacimiento' => '2005-01-15',
                'genero' => 'M',
                'idGrupo' => 1,
                'canalizacion' => false,
                'carrera' => 'Ingeniería en Sistemas',
                'razonesCarrera' => 'Me encanta la tecnología',
                'telefono' => '5512340000',
                'estadoCivil' => 'Soltero/a',
                'email' => 'juan@example.com',
                'municipio' => 'Iztapalapa',
                'estado' => 'CDMX',
                'calleYnumero' => 'Av. Siempre Viva #123',
                'codigoPostal' => '09870',
            ],
            [
                'curp' => 'TEST000001HDFMNR02',
                'contrasena' => Hash::make('12345678'),
                'rol' => 'Estudiante',
                'nombres' => 'María',
                'apellidoPaterno' => 'López',
                'apellidoMaterno' => 'Hernández',
                'fechaNacimiento' => '2004-06-20',
                'genero' => 'F',
                'idGrupo' => 2,
                'canalizacion' => false,
                'carrera' => 'Medicina',
                'razonesCarrera' => 'Me interesa la salud',
                'telefono' => '5512340001',
                'estadoCivil' => 'Soltero/a',
                'email' => 'maria@example.com',
                'municipio' => 'Tlalpan',
                'estado' => 'CDMX',
                'calleYnumero' => 'Calle Luna #456',
                'codigoPostal' => '14300',
            ],
        ];

        foreach ($estudiantes as $estudiante) {
            // Crear usuario
            User::firstOrCreate(
                ['curp' => $estudiante['curp']],
                [
                    'curp' => $estudiante['curp'],
                    'contrasena' => $estudiante['contrasena'],
                    'rol' => $estudiante['rol'],
                ]
            );

            // Crear tutorado
            Tutorado::firstOrCreate(
                ['curp' => $estudiante['curp']],
                [
                    'nombres' => $estudiante['nombres'],
                    'apellidoPaterno' => $estudiante['apellidoPaterno'],
                    'apellidoMaterno' => $estudiante['apellidoMaterno'],
                    'fechaNacimiento' => $estudiante['fechaNacimiento'],
                    'genero' => $estudiante['genero'],
                    'idGrupo' => $estudiante['idGrupo'],
                    'canalizacion' => $estudiante['canalizacion'],
                    'carrera' => $estudiante['carrera'],
                    'razonesCarrera' => $estudiante['razonesCarrera'],
                    'telefono' => $estudiante['telefono'],
                    'estadoCivil' => $estudiante['estadoCivil'],
                    'email' => $estudiante['email'],
                    'municipio' => $estudiante['municipio'],
                    'estado' => $estudiante['estado'],
                    'calleYnumero' => $estudiante['calleYnumero'],
                    'codigoPostal' => $estudiante['codigoPostal'],
                    'discapacidadFisica' => null,
                    'enfemerdad' => null,
                    'situacionPsicologica' => null,
                    'necesidadEspecial' => null,
                    'fotoPerfil' => null,
                ]
            );
        }
    }
}
