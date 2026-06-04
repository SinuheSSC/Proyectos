<div class="w-full">
    {{-- Label for "Actividad" --}}
    <div class="text-center mt-32 mb-4"> {{-- Adjust padding as needed --}}
        <span class="text-lg font-bold text-gray-800">{{$actividad}}</span>
    </div>

    {{-- Listado de tutorados --}}
    <div class="flex flex-col items-center">
        @foreach ($tutoradosActividad as $tutorado)
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
                        <span class="block text-xs text-gray-500 mb-0.5">Estado</span>
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            Terminada
                        </span>
                    </div>

                    <button type="button" wire:click="ir({{ $tutorado->idCuentaTutorado }})"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 flex-shrink-0 w-full sm:w-auto">
                        Ver más
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
