<div
    class="w-[90%] mx-auto bg-gray-100 px-4 py-2 rounded-xl shadow-inner flex justify-between items-center text-xs md:text-base h-auto mb-4 relative">

    <!-- Bandera visual -->
    <div class="absolute top-0 right-0 bg-green-400 text-white text-[10px] px-2 py-1 rounded-bl-lg">
         {{-- {{ ucfirst($vista) }} Mostrará "Tutores" o "Tutorados" --}}
    </div>

    <div class="grid grid-cols-2 gap-x-8 gap-y-2 flex-1 mt-2">
        <div>
            <strong class="text-base font-medium">Nombre del Responsable:</strong>
            {{ $dato->nombres  ?? 'N/D'}} {{ $dato->apellidoPaterno  ?? 'N/D'}} {{ $dato->apellidoMaterno ?? 'N/D'}}
        </div>

        @if ($vista === 'tutores')
            <div><strong class="text-base font-medium">Carrera:</strong> {{ $dato->especialidad ?? 'N/D' }}</div>
            <div><strong class="text-base font-medium">Estado:</strong> {{ $dato->estado ?? 'N/D'}}</div>
        @else
            <div><strong class="text-base font-medium">Edad:</strong> {{ $dato->edad ?? 'N/D' }}</div>
            <div><strong class="text-base font-medium">Semestre:</strong> {{ $dato->semestre ?? 'N/D' }}</div>
        @endif

        <div>
            <strong class="text-base font-medium">Grupo:</strong>
            {{ $dato->grupo->letra ?? 'N/A' }}
        </div>
    </div>

    <div class="ml-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black cursor-pointer hover:text-gray-600"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
        </svg>
    </div>
</div>
