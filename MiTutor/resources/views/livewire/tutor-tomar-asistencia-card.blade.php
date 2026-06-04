<div class="bg-white rounded-lg shadow-md flex flex-col sm:flex-row w-full max-w-[550px] h-auto sm:h-[250px]">
    <!-- Línea roja al lado izquierdo (arriba en móviles) -->
    <div class="w-full sm:w-[5px] h-[5px] sm:h-auto bg-red-400 rounded-t-lg sm:rounded-t-none sm:rounded-l-lg"></div>

    <div class="flex justify-center items-center w-full p-4">
        <div class="w-full">
            <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                <span class="font-semibold text-lg">Tomar asistencia</span>
            </div>
            @if (session()->has('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 mb-3 rounded-md w-11/12 mx-auto"
                    role="alert">
                    <p class="font-medium text-sm">¡Error!</p>
                    <p class="text-xs">{{ session('error') }}</p>
                </div>
            @endif

            <div class="mb-4">
                <label for="grupo" class="block text-sm font-medium text-gray-700">Grupo</label>
                <select id="grupo" wire:model="grupo"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Seleccione el grupo</option>
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->idGrupo }}">{{ $grupo->letra }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-right">
                <button wire:click="guardar"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
                    Seleccionar
                </button>
            </div>
        </div>
    </div>
</div>
