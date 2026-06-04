<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tutor;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutores = [
            [
                'idCuentaTutor' => 1,
                'nombres' => 'Ana Laura',
                'apellidoPaterno' => 'García',
                'apellidoMaterno' => 'Lopez',
                'fechaNacimiento' => '1985-06-15',
                'curp' => 'GALA850615HMCRPN08',
                'RFC' => 'GALA850615ABC',
                'genero' => 'F',
                'cedulaProfesional' => '1234567',
                'especialidad' => 'Psicología Educativa',
                'titulo' => 'Lic. en Psicología',
                'telefono' => '5551234567',
                'estadoCivil' => 'Soltero/a',
                'email' => 'ana@example.com',
                'municipio' => 'Coyoacán',
                'estado' => 'CDMX',
                'calleYnumero' => 'Av. Universidad 123',
                'codigoPostal' => '04360',
                'fotoPerfil' => '/images/tutor/Perfil1.webp'
            ],
            [
                'idCuentaTutor' => 2,
                'nombres' => 'Carlos',
                'apellidoPaterno' => 'Ramírez',
                'apellidoMaterno' => 'Mendoza',
                'fechaNacimiento' => '1979-12-10',
                'curp' => 'RAMC791210HDFMNR07',
                'RFC' => 'RAMC791210XYZ',
                'genero' => 'M',
                'cedulaProfesional' => '7654321',
                'especialidad' => 'Pedagogía',
                'titulo' => 'Mtro. en Educación',
                'telefono' => '5512345678',
                'estadoCivil' => 'Casado/a',
                'email' => 'carlos@example.com',
                'municipio' => 'Benito Juárez',
                'estado' => 'CDMX',
                'calleYnumero' => 'Insurgentes Sur 456',
                'codigoPostal' => '03900',
                'fotoPerfil' => 'default.jpg'
            ],
            [
                'idCuentaTutor' => 3,
                'nombres' => 'Lucía',
                'apellidoPaterno' => 'Fernández',
                'apellidoMaterno' => 'Gómez',
                'fechaNacimiento' => '1990-03-22',
                'curp' => 'FEGL900322MDFLRL03',
                'RFC' => 'FEGL900322DEF',
                'genero' => 'F',
                'cedulaProfesional' => '2468135',
                'especialidad' => 'Orientación Vocacional',
                'titulo' => 'Lic. en Orientación Educativa',
                'telefono' => '5543216789',
                'estadoCivil' => 'Soltero/a',
                'email' => 'lucia@example.com',
                'municipio' => 'Tlalpan',
                'estado' => 'CDMX',
                'calleYnumero' => 'Calzada del Hueso 789',
                'codigoPostal' => '14370',
                'fotoPerfil' => 'default.jpg'
            ]
        ];

        foreach ($tutores as $tutor) {
            Tutor::firstOrCreate(
                ['idCuentaTutor' => $tutor['idCuentaTutor']],
                $tutor
            );
        }
    }
}
