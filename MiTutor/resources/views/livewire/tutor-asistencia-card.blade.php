<div class="w-5/6 h-full mt-1 overflow-y-auto flex-shrink-0 flex flex-col items-center">
    @foreach ($tutorados as $tutorado)
        <div
            class=" p-4 rounded-lg shadow-md flex flex-wrap bg-white items-center justify-center sm:justify-between w-full max-w-[950px] my-[1px]">
            <!-- Nombre -->
            <span class="font-semibold text-gray-800 text-base">
                {{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }} {{ $tutorado->apellidoMaterno }}
            </span>

            <!-- Carrera -->
            <span class="text-sm text-gray-600 hidden md:block">
                {{ $tutorado->carrera }}
            </span>

            <!-- Botón para cambiar asistencia -->
            <button wire:click="cambiarEstadoAsistencia({{ $tutorado->idCuentaTutorado }})"
                class="py-2 px-4 rounded-full text-sm w-24 text-center
                {{ $asistencias[$tutorado->idCuentaTutorado] ?? false ? 'bg-green-400 text-white' : 'bg-red-400 text-white' }}">
                {{ $asistencias[$tutorado->idCuentaTutorado] ?? false ? 'Asistió' : 'No asistió' }}
            </button>

        </div>
    @endforeach
</div>
