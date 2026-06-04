<div class="bg-white rounded-lg shadow-md flex flex-col sm:flex-row w-3/4 h-auto sm:h-[290px]">
    <!-- Línea roja al lado izquierdo (arriba en móviles) -->
    <div class="w-full sm:w-[5px] h-[5px] sm:h-auto bg-red-400 rounded-t-lg sm:rounded-t-none sm:rounded-l-lg"></div>

    <div class="flex justify-center items-center w-full p-4">
        <div class="w-full">
            <div class="flex justify-center flex-col md:flex-row sm:justify-start w-full sm:w-auto">
                <div
                    class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full mx-1 mb-4 sm:mb-2 w-full sm:w-auto flex justify-center">
                    <span class="font-semibold text-lg">{{ $grupo }}</span>
                </div>
                <div
                    class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full mx-1 mb-4 sm:mb-2 w-full sm:w-auto flex justify-center">
                    <span class="font-semibold text-lg">{{ $cicloEscolar }}</span>
                </div>
            </div>

            <div
                class="mb-4 bg-gray-200 w-3/4 flex justify-center flex-col lg:flex-row gap-4 lg:gap-12 p-4 rounded-lg mx-auto">
                <div class="flex flex-col items-center">
                    <h1 class="text-8xl overflow-x-auto overflow-y-hidden">{{ $sesiones }}</h1>
                    <p class="text-lg">Sesiones</p>
                </div>
                <div class="flex flex-col items-center overflow-x-auto">
                    <h1 class="text-8xl">{{ $promAsistencia }}%</h1>
                    <p class="text-lg">Asistencia alumnos</p>
                </div>
                <div class="flex flex-col items-center overflow-x-auto">
                    <h1 class="text-8xl">{{ $atenciones }}</h1>
                    <p class="text-lg">Atenciones</p>
                </div>
            </div>

            <div class="text-right flex justify-center sm:justify-end">
                <button type="button" wire:click="goBack"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                    Volver
                </button>
            </div>

        </div>
    </div>
</div>
