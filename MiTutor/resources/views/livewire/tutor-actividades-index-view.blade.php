<div class="w-full">
    <div class="flex flex-col items-center">
        {{-- Etiqueta "Actividad c" arriba a la izquierda --}}
        <div class="w-[80%] text-left mb-2 my-4">
            <span class="text-lg font-semibold text-gray-700 ">Actividad c</span>
        </div>
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

                    {{-- Botón "Ver más" --}}
                    <a href="{{ route('tutor.tutorados.tutorado.tutorado', ['idCuentaTutorado' => $tutorado->idCuentaTutorado]) }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 flex-shrink-0 w-full sm:w-auto text-center">
                        Ver más
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
