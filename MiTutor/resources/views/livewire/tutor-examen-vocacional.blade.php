<div class="h-full flex justify-center flex-col items-center px-8">
    <div class="flex items-center justify-center">
        <div class="flex items-center space-x-2 bg-[#86A0FE] text-white px-4 mt-10 py-2 rounded-full shadow-md">
            <img src="{{ $tutorado->fotoPerfil }}" alt="Foto de perfil" class="h-8 w-8 rounded-full">
            <span class="font-semibold text-sm">{{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }}
                {{ $tutorado->apellidoMaterno }}</span>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl w-full">
        <!-- Columna 1: Carreras recomendadas -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-blue text-white px-4 py-2 rounded-full inline-block mb-4">
                Carreras recomendadas
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Top 3 carreras para ti:
            </h3>
            <div class="space-y-3">
                <div class="text-gray-700">
                    {{ $respuestaVocacional->carrerasRecomendadas }}
                </div>
            </div>
        </div>

        <!-- Columna 2: Resumen de resultados -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-purple text-white px-4 py-2 rounded-full inline-block mb-4">
                Áreas Recomendadas
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                ¡Aquí están tus resultados!
            </h3>
            <div class="rounded-lg p-4">
                <p class="text-gray-700">
                    {{ $respuestaVocacional->areasDeEspecialidad }}
                </p>
            </div>
        </div>

        <!-- Columna 3: Recomendaciones generales -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-pink text-white px-4 py-2 rounded-full inline-block mb-4">
                Recomendaciones generales
            </div>
            <div class="space-y-3 text-gray-700 text-sm leading-relaxed">
                <p>
                    {{ $respuestaVocacional->recomendaciones }}
                </p>
                <button wire:click="goBack"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
                    Salir
                </button>
            </div>
        </div>
    </div>
</div>
