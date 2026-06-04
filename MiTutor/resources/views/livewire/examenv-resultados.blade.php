<div class="min-h-screen flex justify-center items-start py-8 px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl w-full">

        <!-- Columna 1: Carreras recomendadas -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-blue text-white px-4 py-2 rounded-full inline-block mb-4">
                Carreras recomendadas
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Top 3 carreras para ti:
            </h3>
            @php
                $carreras = array_filter(explode(',', $examen->carrerasRecomendadas));
            @endphp
            <div class="space-y-3">
                @foreach ($carreras as $index => $carrera)
                    <div class="text-gray-700">
                        <strong>{{ $index + 1 }}.-</strong> {{ trim($carrera) }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Columna 2: Resumen de resultados -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-purple text-white px-4 py-2 rounded-full inline-block mb-4">
                Áreas de Especialidad
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                ¡Aquí están tus resultados!
            </h3>
            <div class="rounded-lg p-4">
                <p class="text-gray-700">
                    {{ $examen->areasDeEspecialidad }}
                </p>
            </div>
        </div>

        <!-- Columna 3: Recomendaciones generales -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="bg-custom-pink text-white px-4 py-2 rounded-full inline-block mb-4">
                Recomendaciones generales
            </div>
            <div class="space-y-3 text-gray-700 text-sm leading-relaxed">
                @foreach (explode('.', $examen->recomendaciones) as $recomendacion)
                    @if (trim($recomendacion) !== '')
                        <p>• {{ trim($recomendacion) }}.</p>
                    @endif
                @endforeach
                <button onclick="window.location='{{ route('examenv') }}'"
                    class="bg-gray-300 hover:bg-gray-500 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Salir
                </button>
            </div>
        </div>
    </div>
    <input type="hidden" wire:model="idCuentaTutorado">
</div>
