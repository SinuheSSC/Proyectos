<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tutorado;
use App\Models\Foda;
use App\Models\Lectura;
use App\Models\Vida;

class CuadernoActividades extends Component
{
    public $nombre;
    public $tutorado;
    public $actividades;

    public function mount()
    {
        $curp = session('curp');

        if ($curp) {
            $this->tutorado = Tutorado::where('curp', $curp)->first();
            
            if ($this->tutorado) {
                $this->nombre = $this->tutorado->nombres;
                $this->cargarEstadoActividades();
            }
        } else {
            $this->tutorado = null;
        }
    }

    private function cargarEstadoActividades()
    {
        $idCuentaTutorado = $this->tutorado->idCuentaTutorado;

        // Verificar estado de cada actividad
        $estadoFoda = $this->verificarEstadoFoda($idCuentaTutorado);
        $estadoVida = $this->verificarEstadoVida($idCuentaTutorado);
        $estadoLectura = $this->verificarEstadoLectura($idCuentaTutorado);

        $this->actividades = [
            [
                'key' => 'foda',
                'nombre' => 'Análisis FODA',
                'descripcion' => 'El Análisis FODA (Fortalezas, Oportunidades, Debilidades y Amenazas) es una herramienta de autoconocimiento que ayuda a evaluar la situación personal de un estudiante. Se utiliza para identificar puntos fuertes y áreas de mejora.',
                'ruta' => 'analisisfodae',
                'estado' => $estadoFoda['estado'],
                'texto_boton' => $estadoFoda['texto_boton'],
                'completada' => $estadoFoda['completada'],
                'porcentaje' => $estadoFoda['porcentaje']
            ],
            [
                'key' => 'vida',
                'nombre' => 'Línea de vida',
                'descripcion' => 'La Línea de Vida ayuda a visualizar momentos importantes de la vida, tanto positivos como desafiantes. Sirve para proyectar metas y aprendizajes clave.',
                'ruta' => 'lineavida',
                'estado' => $estadoVida['estado'],
                'texto_boton' => $estadoVida['texto_boton'],
                'completada' => $estadoVida['completada'],
                'porcentaje' => $estadoVida['porcentaje']
            ],
            [
                'key' => 'lectura',
                'nombre' => 'Comprensión lectora',
                'descripcion' => 'La Comprensión Lectora es la habilidad de leer, interpretar y analizar un texto para entender su significado. Permite captar ideas principales y reflexionar sobre el contenido.',
                'ruta' => 'comprensionlectorae',
                'estado' => $estadoLectura['estado'],
                'texto_boton' => $estadoLectura['texto_boton'],
                'completada' => $estadoLectura['completada'],
                'porcentaje' => $estadoLectura['porcentaje']
            ]
        ];
    }

    private function verificarEstadoFoda($idCuentaTutorado)
    {
        $foda = Foda::where('idCuentaTutorado', $idCuentaTutorado)->first();
        
        if (!$foda) {
            return [
                'estado' => 'no_iniciada',
                'texto_boton' => 'Iniciar actividad',
                'completada' => false,
                'porcentaje' => 0
            ];
        }

        // Contar campos completados
        $campos = ['fortaleza', 'debilidad', 'oportunidad', 'amenazas'];
        $camposCompletos = 0;
        
        foreach ($campos as $campo) {
            if (!empty($foda->$campo)) {
                $camposCompletos++;
            }
        }

        $porcentaje = round(($camposCompletos / count($campos)) * 100);

        if ($camposCompletos === count($campos)) {
            return [
                'estado' => 'completada',
                'texto_boton' => 'Volver a realizar',
                'completada' => true,
                'porcentaje' => 100
            ];
        } else if ($camposCompletos > 0) {
            return [
                'estado' => 'en_progreso',
                'texto_boton' => 'Continuar actividad',
                'completada' => false,
                'porcentaje' => $porcentaje
            ];
        } else {
            return [
                'estado' => 'en_progreso',
                'texto_boton' => 'Continuar actividad',
                'completada' => false,
                'porcentaje' => 10 // Mínimo por haber iniciado
            ];
        }
    }

    private function verificarEstadoVida($idCuentaTutorado)
    {
        $vida = Vida::where('idCuentaTutorado', $idCuentaTutorado)->first();
        
        if (!$vida) {
            return [
                'estado' => 'no_iniciada',
                'texto_boton' => 'Iniciar actividad',
                'completada' => false,
                'porcentaje' => 0
            ];
        }

        // Verificar hitos completos (hito + fecha)
        $hitos = [
            ['hito' => 'hitoUno', 'fecha' => 'fechaHitoUno'],
            ['hito' => 'hitoDos', 'fecha' => 'fechaHitoDos'],
            ['hito' => 'hitoTres', 'fecha' => 'fechaHitoTres'],
            ['hito' => 'hitoCuatro', 'fecha' => 'fechaHitoCuatro'],
            ['hito' => 'hitoCinco', 'fecha' => 'fechaHitoCinco']
        ];

        $hitosCompletos = 0;
        foreach ($hitos as $hitoData) {
            if (!empty($vida->{$hitoData['hito']}) && !empty($vida->{$hitoData['fecha']})) {
                $hitosCompletos++;
            }
        }

        $porcentaje = round(($hitosCompletos / count($hitos)) * 100);

        if ($hitosCompletos >= 3) { // Consideramos completa con 3+ hitos
            return [
                'estado' => 'completada',
                'texto_boton' => 'Volver a realizar',
                'completada' => true,
                'porcentaje' => 100
            ];
        } else if ($hitosCompletos > 0) {
            return [
                'estado' => 'en_progreso',
                'texto_boton' => 'Continuar actividad',
                'completada' => false,
                'porcentaje' => max($porcentaje, 20) // Mínimo 20%
            ];
        } else {
            return [
                'estado' => 'en_progreso',
                'texto_boton' => 'Continuar actividad',
                'completada' => false,
                'porcentaje' => 10 // Mínimo por haber iniciado
            ];
        }
    }

    private function verificarEstadoLectura($idCuentaTutorado)
    {
        $lectura = Lectura::where('idCuentaTutorado', $idCuentaTutorado)->first();
        
        if (!$lectura) {
            return [
                'estado' => 'no_iniciada',
                'texto_boton' => 'Iniciar actividad',
                'completada' => false,
                'porcentaje' => 0
            ];
        }

        // Si existe y tiene un nivel de comprensión registrado, está completa
        if (!empty($lectura->nivelComprensionLectora)) {
            return [
                'estado' => 'completada',
                'texto_boton' => 'Volver a realizar',
                'completada' => true,
                'porcentaje' => 100
            ];
        } else {
            return [
                'estado' => 'en_progreso',
                'texto_boton' => 'Continuar actividad',
                'completada' => false,
                'porcentaje' => 50 // En progreso
            ];
        }
    }

    // Método para obtener estadísticas generales
    public function getProgresoGeneral()
    {
        if (!$this->actividades) {
            return ['completadas' => 0, 'total' => 0, 'porcentaje' => 0];
        }

        $completadas = collect($this->actividades)->where('completada', true)->count();
        $total = count($this->actividades);
        $porcentaje = $total > 0 ? round(($completadas / $total) * 100) : 0;

        return [
            'completadas' => $completadas,
            'total' => $total,
            'porcentaje' => $porcentaje
        ];
    }

    public function render()
    {
        return view('livewire.cuaderno-actividades');
    }
}