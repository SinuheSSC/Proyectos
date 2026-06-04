<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tutorado;

class TutoradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorados = [
            [
                'nombres' => 'Luis Alberto',
                'apellidoPaterno' => 'Hernández',
                'apellidoMaterno' => 'Soto',
                'fechaNacimiento' => '2003-05-12',
                'curp' => 'HESL030512HDFNRL09',
                'genero' => 'M',
                'idGrupo' => 1,
                'canalizacion' => false,
                'carrera' => 'Ingeniería en Sistemas',
                'razonesCarrera' => 'Me gusta la tecnología',
                'telefono' => '5512340001',
                'estadoCivil' => 'Soltero/a',
                'email' => 'luis@example.com',
                'municipio' => 'Iztapalapa',
                'estado' => 'CDMX',
                'calleYnumero' => 'Calle 1 #123',
                'codigoPostal' => '09870',
                'discapacidadFisica' => null,
                'enfemerdad' => 'Diabetes',
                'situacionPsicologica' => null,
                'necesidadEspecial' => 'Jugador de fornite',
                'fotoPerfil' => 'Perfil2.png',
            ],
            [
                'nombres' => 'Carlos Iván',
                'apellidoPaterno' => 'Zúñiga',
                'apellidoMaterno' => 'Flores',
                'fechaNacimiento' => '2003-01-15',
                'curp' => 'ZUFC030115HDFLRS04',
                'genero' => 'M',
                'idGrupo' => 1,
                'canalizacion' => false,
                'carrera' => 'Ingeniería Civil',
                'razonesCarrera' => 'Quiero construir obras útiles',
                'telefono' => '5512340004',
                'estadoCivil' => 'Soltero/a',
                'email' => 'carlos@example.com',
                'municipio' => 'Xochimilco',
                'estado' => 'CDMX',
                'calleYnumero' => 'Prolongación División #101',
                'codigoPostal' => '16000',
                'discapacidadFisica' => null,
                'enfemerdad' => null,
                'situacionPsicologica' => null,
                'necesidadEspecial' => null,
                'fotoPerfil' => null,
            ],
            [
                'nombres' => 'Ana Sofía',
                'apellidoPaterno' => 'Martínez',
                'apellidoMaterno' => 'Reyes',
                'fechaNacimiento' => '2002-10-10',
                'curp' => 'MARR021010MDFRYN06',
                'genero' => 'F',
                'idGrupo' => 1,
                'canalizacion' => true,
                'carrera' => 'Medicina',
                'razonesCarrera' => 'Quiero salvar vidas',
                'telefono' => '5512340005',
                'estadoCivil' => 'Soltero/a',
                'email' => 'ana@example.com',
                'municipio' => 'Benito Juárez',
                'estado' => 'CDMX',
                'calleYnumero' => 'Insurgentes Sur #400',
                'codigoPostal' => '03900',
                'discapacidadFisica' => 'Ninguna',
                'enfemerdad' => 'Hipotiroidismo',
                'situacionPsicologica' => 'Estrés académico',
                'necesidadEspecial' => 'Tiempo extra en exámenes',
                'fotoPerfil' => 'Perfil3.png',
            ],
            [
                'nombres' => 'María Fernanda',
                'apellidoPaterno' => 'Cruz',
                'apellidoMaterno' => 'López',
                'fechaNacimiento' => '2002-11-22',
                'curp' => 'RAMC791210HDFMNR07',
                'genero' => 'F',
                'idGrupo' => 2,
                'canalizacion' => true,
                'carrera' => 'Psicología',
                'razonesCarrera' => 'Quiero ayudar a las personas',
                'telefono' => '5512340002',
                'estadoCivil' => 'Soltero/a',
                'email' => 'maria@example.com',
                'municipio' => 'Tlalpan',
                'estado' => 'CDMX',
                'calleYnumero' => 'Av. 2 #456',
                'codigoPostal' => '14300',
                'discapacidadFisica' => 'Ninguna',
                'enfemerdad' => 'Asma',
                'situacionPsicologica' => null,
                'necesidadEspecial' => null,
                'fotoPerfil' => null,
            ],
            [
                'nombres' => 'Jorge Andrés',
                'apellidoPaterno' => 'Mendoza',
                'apellidoMaterno' => 'Ramírez',
                'fechaNacimiento' => '2004-08-30',
                'curp' => 'FEGL900322MDFLRL03',
                'genero' => 'M',
                'idGrupo' => 3,
                'canalizacion' => false,
                'carrera' => 'Arquitectura',
                'razonesCarrera' => 'Interés en diseño y arte',
                'telefono' => '5512340003',
                'estadoCivil' => 'Soltero/a',
                'email' => 'jorge@example.com',
                'municipio' => 'Coyoacán',
                'estado' => 'CDMX',
                'calleYnumero' => 'Col. del Valle #789',
                'codigoPostal' => '03100',
                'discapacidadFisica' => null,
                'enfemerdad' => null,
                'situacionPsicologica' => 'Ansiedad leve',
                'necesidadEspecial' => null,
                'fotoPerfil' => null,
            ]
        ];

        // --- Generar 10 estudiantes de "Ingeniería en Sistemas" y "Grupo 1" ---
        $nombresComunes = ['Alejandro', 'Brenda', 'Cristian', 'Daniela', 'Eduardo', 'Fabiola', 'Gabriel', 'Itzel', 'Javier', 'Karla'];
        $apellidosComunes = ['Pérez', 'García', 'Rodríguez', 'Hernández', 'López', 'González', 'Martínez', 'Sánchez'];
        $curpCounter = 0; // Para el sufijo del CURP, empezando desde 00

        for ($i = 0; $i < 10; $i++) {
            $curpBaseSuffix = '000000HDFMNR'; // Para que coincida con el formato TEST000000HDFMNRxx
            // Empezamos los sufijos numéricos de los CURP de los nuevos estudiantes desde 03,
            // asumiendo que los primeros 3 CURPs de TEST... son TEST...00, TEST...01, TEST...02
            $curpSuffix = str_pad($curpCounter + 3, 2, '0', STR_PAD_LEFT);
            $curpCounter++; // Incrementa para el siguiente

            $nombresRandom = $nombresComunes[$i];
            $apellidoPaternoRandom = $apellidosComunes[array_rand($apellidosComunes)];
            $apellidoMaternoRandom = $apellidosComunes[array_rand($apellidosComunes)];
            $generoRandom = ($i % 2 == 0) ? 'M' : 'F'; // Alterna género
            $fechaNacimientoRandom = '200' . rand(3, 5) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

            $tutorados[] = [
                'nombres' => $nombresRandom,
                'apellidoPaterno' => $apellidoPaternoRandom,
                'apellidoMaterno' => $apellidoMaternoRandom,
                'fechaNacimiento' => $fechaNacimientoRandom,
                'curp' => 'TEST' . $curpBaseSuffix . $curpSuffix,
                'genero' => $generoRandom,
                'idGrupo' => 1, // Especificado: idGrupo 1
                'canalizacion' => false,
                'carrera' => 'Ingeniería en Sistemas', // Especificado: Ingeniería en Sistemas
                'razonesCarrera' => 'Me encanta la tecnología', // Texto similar a los existentes
                'telefono' => '55' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'estadoCivil' => 'Soltero/a',
                'email' => strtolower(str_replace(' ', '', $nombresRandom)) . $curpSuffix . '@example.com',
                'municipio' => 'Morelia',
                'estado' => 'Michoacán',
                'calleYnumero' => 'Av. Tecnológica #' . (100 + $i),
                'codigoPostal' => '5800' . ($i % 10),
                'discapacidadFisica' => null,
                'enfemerdad' => null,
                'situacionPsicologica' => null,
                'necesidadEspecial' => null,
                'fotoPerfil' => 'perfil_sistemas_' . ($i + 1) . '.png',
            ];
        }

        // --- Generar 10 estudiantes variados ---
        $carreras = ['Derecho', 'Arquitectura', 'Contaduría', 'Diseño Gráfico', 'Psicología', 'Nutrición', 'Comunicación'];
        $municipios = ['Guadalajara', 'Monterrey', 'Puebla', 'Cancún', 'Veracruz', 'León'];
        $estados = ['Jalisco', 'Nuevo León', 'Puebla', 'Quintana Roo', 'Veracruz', 'Guanajuato'];
        $nombresVariados = ['Sofía', 'Daniel', 'Valentina', 'Mateo', 'Isabella', 'Sebastián', 'Victoria', 'Nicolás', 'Camila', 'Benjamín'];

        for ($i = 0; $i < 10; $i++) {
            $curpBaseSuffix = '000000HDFMNR';
            // Continuamos la secuencia de sufijos numéricos para los CURP
            $curpSuffix = str_pad($curpCounter + 3, 2, '0', STR_PAD_LEFT);
            $curpCounter++; // Incrementa para el siguiente

            $nombresRandom = $nombresVariados[array_rand($nombresVariados)];
            $apellidoPaternoRandom = $apellidosComunes[array_rand($apellidosComunes)];
            $apellidoMaternoRandom = $apellidosComunes[array_rand($apellidosComunes)];
            $generoRandom = (mt_rand(0, 1) == 0) ? 'M' : 'F';
            $fechaNacimientoRandom = '200' . rand(2, 5) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $idGrupoRandom = rand(1, 3); // ¡Corregido! Grupos solo del 1 al 3
            $carreraRandom = $carreras[array_rand($carreras)];
            $municipioRandom = $municipios[array_rand($municipios)];
            $estadoRandom = $estados[array_rand($estados)];
            $canalizacionRandom = (bool)rand(0, 1); // Aleatorio true/false

            $discapacidadFisicaOptions = [null, 'Visual parcial', 'Auditiva', 'Movilidad reducida'];
            $enfemerdadOptions = [null, 'Asma', 'Alergias', 'Hipertensión', 'Migrañas'];
            $situacionPsicologicaOptions = [null, 'Estrés', 'Ansiedad', 'Depresión leve'];
            $necesidadEspecialOptions = [null, 'Apoyo psicológico', 'Material didáctico adaptado', 'Tutorías extra'];

            $tutorados[] = [
                'nombres' => $nombresRandom,
                'apellidoPaterno' => $apellidoPaternoRandom,
                'apellidoMaterno' => $apellidoMaternoRandom,
                'fechaNacimiento' => $fechaNacimientoRandom,
                'curp' => 'TEST' . $curpBaseSuffix . $curpSuffix,
                'genero' => $generoRandom,
                'idGrupo' => $idGrupoRandom,
                'canalizacion' => $canalizacionRandom,
                'carrera' => $carreraRandom,
                'razonesCarrera' => 'Me interesa ' . strtolower($carreraRandom), // Texto variado para razonesCarrera
                'telefono' => '55' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'estadoCivil' => 'Soltero/a',
                'email' => strtolower(str_replace(' ', '', $nombresRandom)) . $curpSuffix . 'v@example.com',
                'municipio' => $municipioRandom,
                'estado' => $estadoRandom,
                'calleYnumero' => 'Calle Principal #' . (200 + $i),
                'codigoPostal' => '0000' . ($i % 10),
                'discapacidadFisica' => $discapacidadFisicaOptions[array_rand($discapacidadFisicaOptions)],
                'enfemerdad' => $enfemerdadOptions[array_rand($enfemerdadOptions)],
                'situacionPsicologica' => $situacionPsicologicaOptions[array_rand($situacionPsicologicaOptions)],
                'necesidadEspecial' => $necesidadEspecialOptions[array_rand($necesidadEspecialOptions)],
                'fotoPerfil' => 'perfil_variado_' . ($i + 1) . '.png',
            ];
        }

        // Inserta todos los tutorados en la base de datos
        foreach ($tutorados as $tutorado) {
            Tutorado::create($tutorado);
        }
    }
}
