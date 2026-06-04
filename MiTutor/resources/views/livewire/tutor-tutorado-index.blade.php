<div class="mx-auto px-4 py-8 w-full h-full flex items-center justify-center">
    {{-- Contenedor principal de las tarjetas, usando grid para layout responsivo --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-[80%]">
        {{-- Primera columna (o fila completa en móvil) --}}
        <div class="md:col-span-1">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4 flex flex-col md:flex-row items-center gap-4">
                <div
                    class="flex-shrink-0 w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-gray-200">
                    {{-- Aquí iría la imagen del alumno --}}
                    <img src="{{ $alumno->foto_perfil }}" alt="Foto de {{ $alumno->nombre }}"
                        class="w-full h-full object-cover">
                </div>

                <div class="flex-grow text-center md:text-left">
                    <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                        <span class="font-semibold text-lg">Información General</span>
                    </div>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p><strong>Nombre:</strong> {{ $alumno->nombre }}</p>
                        <p><strong>Apellido paterno:</strong> {{ $alumno->apellido_paterno }}</p>
                        <p><strong>Apellido materno:</strong> {{ $alumno->apellido_materno }}</p>
                        <p><strong>Fecha de nacimiento:</strong>
                            {{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</p>
                        <p><strong>Género:</strong> {{ $alumno->genero }}</p>
                        <p><strong>CURP:</strong> {{ $alumno->curp }}</p>
                        @if (isset($alumno->tutor_asignado))
                            {{-- Si tienes un campo para el tutor asignado --}}
                            <p><strong>Tutor asignado:</strong> {{ $alumno->tutor_asignado }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Segunda columna (o debajo en móvil) --}}
        <div class="md:col-span-1">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                    <span class="font-semibold text-lg">Necesidad especial</span>
                </div>

                <div class="text-sm text-gray-700 space-y-1 mt-2">
                    <p><strong>Discapacidad:</strong> <span
                            class="{{ strtoupper($alumno->discapacidad ?? 'NO') === 'SI' ? 'text-red-600 font-semibold' : 'text-green-600' }}">{{ strtoupper($alumno->discapacidad ?? 'NO') }}
                            CONFIRMADO</span></p>
                    <p><strong>Enfermedad:</strong> <span
                            class="{{ strtoupper($alumno->enfermedad ?? 'NO') === 'SI' ? 'text-red-600 font-semibold' : 'text-green-600' }}">{{ strtoupper($alumno->enfermedad ?? 'NO') }}</span>
                    </p>
                    <p><strong>Condición especial:</strong> <span
                            class="{{ strtoupper($alumno->condicion_especial ?? 'NO') === 'SI' ? 'text-red-600 font-semibold' : 'text-green-600' }}">{{ strtoupper($alumno->condicion_especial ?? 'NO') }}</span>
                    </p>
                </div>
                {{-- Contenedor del botón "Consultar" adaptado para responsividad --}}
                <div class="mt-4 flex justify-center md:justify-end">
                    <button type="button" wire:click="consultarNecesidad({{ $alumno->id }})"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                        Consultar
                    </button>
                </div>
            </div>
        </div>

        {{-- La tarjeta de actividades siempre ocupará todo el ancho en móviles y md+.
              En la imagen original, está debajo de ambas columnas. --}}
        <div class="col-span-full"> {{-- Ocupa las dos columnas en md+, y todo el ancho en móvil --}}
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                    <span class="font-semibold text-lg">Actividades</span>
                </div>
                @if (session()->has('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-1 mb-3 rounded-md w-32 mx-auto"
                        role="alert">
                        <p class="text-xs">{{ session('error') }}</p>
                    </div>
                @endif
                {{-- Contenedor con altura máxima y scroll --}}
                <div class="space-y-3 mt-3 max-h-40 overflow-y-auto">
                    @forelse ($alumno->actividades as $actividad)
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-center p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center flex-grow mb-2 sm:mb-0">
                                <span
                                    class="font-bold text-gray-700 mr-4 flex-shrink-0">{{ $actividad->numero }}</span>
                                <span class="text-gray-800 flex-grow">{{ $actividad->nombre }}</span>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row items-center sm:items-center sm:justify-end ml-0 sm:ml-4 flex-shrink-0 w-full sm:w-[70%] ">
                                @if (strtoupper($actividad->estado) == 'NO')
                                    <span
                                        class="inline-flex items-center px-2 py-1  rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 mb-2 sm:mb-0 sm:mr-2">
                                        <span class="bg-yellow-500 mr-1"></span>
                                        {{ $actividad->estado }}
                                    </span>
                                @else
                                    @switch($actividad->numero)
                                        @case('01')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-2">
                                                <span class="bg-blue-300 mr-1 h-2 w-2 rounded-full"></span>
                                                Nivel de comprensión | {{ $actividad->nivelComprensionLectora }}
                                            </span>
                                        @break

                                        @case('02')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-2">
                                                <span class="bg-blue-300 mr-1 h-2 w-2 rounded-full"></span>
                                                FODA finalizado
                                            </span>
                                        @break

                                        @case('03')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-2">
                                                <span class="bg-blue-300 mr-1 h-2 w-2 rounded-full"></span>
                                                Línea de Vida finalizada
                                            </span>
                                        @break

                                        @case('04')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600 mb-2 sm:mb-0 sm:mr-2">
                                                <span class="bg-blue-300 mr-1 h-2 w-2 rounded-full"></span>
                                                Examen vocacional finalizado
                                            </span>
                                        @break
                                    @endswitch
                                @endif
                                {{-- Botón "Consultar" para CADA ACTIVIDAD --}}
                            </div>
                            <div class="mt-2 sm:mt-0 sm:ml-4 sm:w-fit w-full flex justify-center sm:justify-end">
                                <button type="button" wire:click="consultarActividad({{ $actividad->numero }})"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                    Consultar
                                </button>
                            </div>
                        </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No hay actividades registradas para este alumno.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
