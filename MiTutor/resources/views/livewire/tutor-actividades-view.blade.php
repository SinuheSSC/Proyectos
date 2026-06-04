<div class="w-full">
    {{-- Poner foreach activi --}}
    <div class="flex flex-col items-center">
        @foreach ($actividades as $actividad)
            <div
                class="bg-white w-[70%] p-4 rounded-lg shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
                <div
                    class="flex-grow flex flex-col md:flex-row items-center justify-between md:items-baseline space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $actividad->numero }}
                    </span>

                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $actividad->nombre }}
                    </span>

                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $actividad->tipo }}
                    </span>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-auto justify-center">

                    {{-- Porcentaje de Actividades --}}
                    <div class="text-center flex-shrink-0">
                        @php
                            $actividadesColorClass = 'bg-gray-200 text-gray-800'; // Default
                            if ($actividad->terminado === 'CONCLUIDA') {
                                $actividadesColorClass = 'bg-green-100 text-green-800';
                            } elseif ($actividad->terminado === 'EN PROCESO') {
                                $actividadesColorClass = 'bg-yellow-100 text-yellow-800';
                            } else {
                                $actividadesColorClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $actividadesColorClass }}">
                            {{ $actividad->terminado }}
                        </span>
                    </div>

                    {{-- Botón "Ver más" --}}
                    <button wire:click="verDetalles({{ $actividad->numero }})"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 flex-shrink-0 w-full sm:w-auto">
                        Ver más
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
