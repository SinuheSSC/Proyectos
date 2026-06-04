<div class="w-full"> {{-- Contenedor principal que asegura el ancho completo --}}
    <div class="flex items-center justify-center">
        <div class="flex items-center space-x-2 bg-[#86A0FE] text-white px-4 mt-10 py-2 rounded-full shadow-md">
            <img src="{{ $tutorado->fotoPerfil }}" alt="Foto de perfil" class="h-8 w-8 rounded-full">
            <span class="font-semibold text-sm">{{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }}
                {{ $tutorado->apellidoMaterno }}</span>
        </div>
    </div>
    <!-- Línea roja al lado izquierdo (arriba en móviles) -->
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-lg shadow-md flex flex-col sm:flex-row w-3/4 h-auto sm:h-[280px]">
            <div class="flex justify-center items-center w-full p-4">
                <div class="w-full h-fit">
                    <div class="flex flex-col lg:flex-row justify-center lg:justify-start">
                        <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-full lg:w-max mx-1 mb-4 sm:mb-2"
                            style="text-align: center;">
                            <span class="font-semibold text-lg">Comprension Lectora</span>
                        </div>
                        <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-full lg:w-max mx-1 mb-4 sm:mb-2 justify-center"
                            style="text-align: center;">
                            <span class="font-semibold text-lg">Resultados</span>
                        </div>
                    </div>

                    <div
                        class="mb-4 bg-gray-200 w-3/4 flex justify-center flex-col lg:flex-row gap-4 lg:gap-12 p-4 rounded-lg mx-auto">
                        <div class="flex flex-col items-center justify-center ">
                            <h1 class="text-3xl md:text-6xl my-auto whitespace-nowrap">
                                {{ $respuestaReading->nivelComprensionLectora }}
                            </h1>
                            <p class="text-lg w-fit my-auto">Comprensión</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <button wire:click="goBack"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
                            Salir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
