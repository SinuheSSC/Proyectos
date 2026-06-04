<div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-4 p-4 bg-white rounded-lg shadow-md w-[85%]">
    <!-- Fecha seleccionada -->
    <div class="bg-[#FFEEEC] text-[#cc928b] px-4 py-2 flex-shrink-0 rounded-full lg:w-40 w-full overflow-x-auto flex justify-center">
        {{ \Carbon\Carbon::parse($fechaSeleccionada)->translatedFormat('d - F') }}
    </div>

    <!-- Contenedor de fechas -->
    <div class="flex overflow-x-auto space-x-2 lg:space-x-4 w-full">
        @foreach ($fechas as $fecha)
            <button
                wire:click="seleccionarFecha('{{ $fecha['valor'] }}')"
                class="w-32 flex-shrink-0 px-4 py-2 rounded-full text-sm {{ $fecha['valor'] == $fechaSeleccionada ? 'bg-[#FFEEEC] text-[#cc928b]' : 'text-gray-700 hover:bg-[#FFEEEC] hover:text-[#cc928b]' }}">
                {{ $fecha['formateado'] }}
            </button>
        @endforeach
    </div>

    <!-- Botón de guardar -->
    <a href="{{ route('tutor.index') }}" class="bg-gray-300 text-black px-4 py-1 rounded-md lg:w-40 w-full text-center">
    Terminar
</a>

</div>



