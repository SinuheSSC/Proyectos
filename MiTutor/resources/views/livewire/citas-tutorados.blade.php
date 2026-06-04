<div class="min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <!-- Encabezado del mes con flechas -->
        <div class="flex justify-between items-center mb-4">
            <button wire:click="mesAnterior" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <h2 class="text-xl font-semibold text-gray-800 uppercase">{{ strtoupper($mesActual) }} {{ $añoActual }}</h2>
            <button wire:click="mesSiguiente" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Lista de citas -->
        <div class="space-y-3">
            @forelse ($citas as $cita)
                <div class="flex justify-between items-center bg-[#86A0FE] text-white px-4 py-2 rounded-lg">
                    <div class="text-sm">
                        @if($cita->descripcion)
                            {{ $cita->descripcion }}
                        @else
                            Cita {{ $cita->tipo ?? 'programada' }}
                            @if($cita->canalizacion)
                                - {{ $cita->canalizacion }}
                            @endif
                        @endif
                    </div>
                    <div class="text-sm">
                        {{ \Carbon\Carbon::parse($cita->fechaCita)->locale('es')->isoFormat('dddd, DD [de] MMMM [de] YYYY') }}
                        @if(\Carbon\Carbon::parse($cita->fechaCita)->format('H:i') !== '00:00')
                            - {{ \Carbon\Carbon::parse($cita->fechaCita)->format('h:i A') }}
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <p>No hay citas programadas para {{ strtolower($mesActual) }} {{ $añoActual }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>