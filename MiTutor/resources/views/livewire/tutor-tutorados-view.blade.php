<div class="w-full">

    <div class="relative mt-32 mb-6 mx-auto w-[80%]">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
            </svg>
        </div>
        <input type="search" wire:model.live.debounce.300ms="search" {{-- Aquí usamos 'search' --}}
            placeholder="Buscar por nombre o carrera..."
            class="w-full pl-10 pr-4 py-2 border border-gray-500 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <div class="flex flex-col items-center">

        @foreach ($tutorados as $tutorado)
            <div
                class="bg-white w-[80%] p-4 rounded-lg shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
                <div
                    class="flex-grow flex flex-col md:flex-row items-center md:items-baseline space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }} {{ $tutorado->apellidoMaterno }}
                    </span>

                    <span class="text-sm text-gray-600 hidden md:block text-center md:text-left">
                        {{ $tutorado->carrera }}
                    </span>
                </div>

                <div
                    class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-auto justify-center">
                    {{-- Asistencia --}}
                    <div class="text-center flex-shrink-0">
                        <span class="block text-xs text-gray-500 mb-0.5">Asistencia</span>
                        @php
                            $porcAsistencias = $tutorado->porcentajeAsistencia;
                            $asistenciaColorClass = 'bg-gray-200 text-gray-800';
                            if ($porcAsistencias >= 80) {
                                $asistenciaColorClass = 'bg-green-100 text-green-800';
                            } elseif ($porcAsistencias >= 50) {
                                $asistenciaColorClass = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $asistenciaColorClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $asistenciaColorClass }}">
                            {{ $tutorado->asistenciasTotales }} / 24 ({{ $porcAsistencias }}%)
                        </span>
                    </div>

                    {{-- Actividades (Estático por ahora) --}}
                    <div class="text-center flex-shrink-0">
                        <span class="block text-xs text-gray-500 mb-0.5">Actividades</span>
                        @php
                            $porcActividades = $tutorado->porcentajeActividades;
                            $asistenciaColorClass = 'bg-gray-200 text-gray-800';
                            if ($porcActividades >= 75) {
                                $asistenciaColorClass = 'bg-green-100 text-green-800';
                            } elseif ($porcActividades >= 50) {
                                $asistenciaColorClass = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $asistenciaColorClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $asistenciaColorClass }}">
                            {{ $tutorado->actividadesHechas }} / 4 ({{ $porcActividades }}%)
                        </span>
                    </div>

                    {{-- Necesidad --}}
                    <div class="text-center flex-shrink-0">
                        <span class="block text-xs text-gray-500 mb-0.5">Necesidad</span>
                        @php
                            $necesidad = $tutorado->necesidadEspecial ? 'SI' : 'NO';
                            $necesidadColorClass =
                                $necesidad === 'SI' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                        @endphp
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $necesidadColorClass }}">
                            {{ $necesidad }}
                        </span>
                    </div>

                    {{-- Botón "Ver más" --}}
                    <button type="button" wire:click="more({{ $tutorado->idCuentaTutorado }})"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 flex-shrink-0 w-full sm:w-auto text-center">
                        Ver más
                    </button>
                </div>
            </div>
        @endforeach

        {{-- Aquí se añaden los enlaces de paginación --}}
        <div class="mt-8 mb-6 mx-auto w-[80%]">
            {{ $tutorados->links() }}
        </div>
    </div>
</div>
