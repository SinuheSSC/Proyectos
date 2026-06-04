@extends('layouts.template-estudiante')

@section('content')
<div class="min-h-screen py-8 px-4">
    @if($tutorado)
        <h1 class="text-2xl font-bold text-gray-800 mb-6">¡Hola {{$tutorado->nombres}}, aquí están tus actividades!</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto">
            @foreach($actividades as $actividad)
                <div class="bg-white rounded-lg shadow p-4 relative overflow-hidden">
                    <!-- Badge de estado en la esquina superior derecha -->
                    <div class="absolute top-2 right-2 z-10">
                        @if($actividad['estado'] === 'completada')
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full flex items-center shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Completada
                            </span>
                        @elseif($actividad['estado'] === 'en_progreso')
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full flex items-center shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                En progreso
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full shadow-sm">
                                Pendiente
                            </span>
                        @endif
                    </div>

                    <!-- Título de la actividad -->
                    <div class="bg-[#86A0FE] text-white px-4 py-2 rounded-full inline-block mb-4 relative z-10">
                        {{ $actividad['nombre'] }}
                    </div>
                    
                    <!-- Descripción -->
                    <p class="text-gray-700 mb-4 text-sm leading-relaxed">
                        {{ $actividad['descripcion'] }}
                    </p>

                    <!-- Barra de progreso -->
                    @if($actividad['estado'] !== 'no_iniciada')
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600">Progreso</span>
                                <span class="text-xs font-medium text-gray-800">{{ $actividad['porcentaje'] }}%</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500 ease-out
                                            @if($actividad['estado'] === 'completada') bg-gradient-to-r from-green-400 to-green-500
                                            @elseif($actividad['estado'] === 'en_progreso') bg-gradient-to-r from-yellow-400 to-yellow-500
                                            @else bg-gradient-to-r from-gray-300 to-gray-400 @endif" 
                                     style="width: {{ $actividad['porcentaje'] }}%">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Botón de acción -->
                    <div class="flex justify-end">
                        <button 
                            onclick="window.location='{{ route($actividad['ruta']) }}'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-sm
                                   @if($actividad['estado'] === 'completada') 
                                       bg-green-500 hover:bg-green-600 text-white hover:shadow-md
                                   @elseif($actividad['estado'] === 'en_progreso') 
                                       bg-yellow-500 hover:bg-yellow-600 text-white hover:shadow-md
                                   @else 
                                       bg-[#86A0FE] hover:bg-[#7190FD] text-white hover:shadow-md
                                   @endif">
                            {{ $actividad['texto_boton'] }}
                        </button>
                    </div>

                    <!-- Decoración de fondo sutil -->
                    <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-gradient-to-br from-[#86A0FE]/10 to-transparent rounded-full"></div>
                </div>
            @endforeach
        </div>

        <!-- Resumen de progreso general -->
        <div class="max-w-7xl mx-auto mt-8">
            <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-[#86A0FE]">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#86A0FE]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1V8z" clip-rule="evenodd"></path>
                    </svg>
                    Tu progreso general
                </h2>
                
                @php
                    $progresoGeneral = $this->getProgresoGeneral();
                    $completadas = $progresoGeneral['completadas'];
                    $total = $progresoGeneral['total'];
                    $porcentaje = $progresoGeneral['porcentaje'];
                @endphp
                
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#86A0FE] to-[#7190FD] h-6 rounded-full transition-all duration-700 ease-out flex items-center justify-end pr-2" 
                                 style="width: {{ max($porcentaje, 5) }}%">
                                @if($porcentaje > 20)
                                    <span class="text-white text-xs font-medium">{{ $porcentaje }}%</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-sm text-gray-600">{{ $completadas }} de {{ $total }} actividades</span>
                            @if($porcentaje <= 20)
                                <span class="text-sm font-medium text-[#86A0FE]">{{ $porcentaje }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Mensajes motivacionales -->
                <div class="mt-4">
                    @if($completadas === $total && $total > 0)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-green-800 font-medium">🎉 ¡Excelente trabajo!</p>
                                <p class="text-green-700 text-sm mt-1">Has completado todas las actividades disponibles. Tu dedicación al autoconocimiento es admirable.</p>
                            </div>
                        </div>
                    @elseif($completadas > 0)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-blue-800 font-medium">¡Vas por buen camino!</p>
                                <p class="text-blue-700 text-sm mt-1">Continúa trabajando en las actividades restantes para completar tu proceso de autoconocimiento.</p>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-start">
                            <svg class="w-5 h-5 text-gray-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-gray-800 font-medium">¡Comienza tu viaje!</p>
                                <p class="text-gray-700 text-sm mt-1">Estas actividades te ayudarán a conocerte mejor y planificar tu futuro académico y profesional.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @else
        <div class="flex items-center justify-center min-h-screen">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Sesión no válida</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontró información del usuario.</p>
            </div>
        </div>
    @endif
</div>
@endsection
