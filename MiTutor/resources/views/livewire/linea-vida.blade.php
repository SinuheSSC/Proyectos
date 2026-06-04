<div class="min-h-screen p-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Instrucciones -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-r-lg">
            <h2 class="text-blue-500 font-semibold text-lg mb-4">Instrucciones para realizar la actividad:</h2>
            <p class="text-gray-800 mb-4">
                <strong>Paso 1:</strong> Piensa en eventos clave de tu vida: Identifica de 3 a 5 momentos importantes que hayan marcado tu desarrollo personal, académico o emocional. Pueden ser logros, desafíos o aprendizajes significativos.
            </p>
            <p class="text-gray-800">
                <strong>Paso 2:</strong> Registra cada evento en la Línea de Vida. Para cada evento, completa los siguientes campos:
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Año en que ocurrió</li>
                    <li>Descripción del hito (Explica en una o dos frases por qué ese evento es importante para ti)</li>
                </ul>
            </p>
        </div>

        <!-- Formulario -->
        <div class="overflow-x-auto">
            <!-- Mensajes de éxito/error -->
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="guardar">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="px-4 py-2 text-left font-medium">Fecha</th>
                            <th class="px-4 py-2 text-left font-medium">Nombre y descripción del hito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <!-- Fecha -->
                            <td class="px-4 py-2 border-b">
                                <input
                                    type="date"
                                    wire:model="fechas.{{ $i }}"
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                            </td>
                            <!-- Descripción del hito -->
                            <td class="px-4 py-2 border-b">
                                <textarea
                                    wire:model="hitos.{{ $i }}"
                                    class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    rows="2"
                                    placeholder="Describe el hito importante de tu vida..."
                                ></textarea>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>

                <div class="mt-4 flex justify-end gap-2">
                    <button
                        type="submit"
                        class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-4">
            <button
                onclick="window.location='{{ route('cuaderno.actividades') }}'"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
            >
                Salir
            </button>
        </div>
    </div>
</div>
