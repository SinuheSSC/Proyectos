<div>
    <div class="flex bg-gray-200 p-2 rounded-lg text-lg w-[280px] mt-16 ml-6">
        <!-- Botón Tutores -->
        <button wire:click="cambiarVista('tutores')" class="flex items-center space-x-1 px-4 py-2 rounded-lg transition-all duration-200
            {{ $vistaActual === 'tutores' ? 'bg-green-400 text-white' : 'bg-transparent text-black' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                    d="M3 4a1 1 0 011-1h3a1 1 0 011 1v1H3V4zm0 2h6v3H3V6zm0 5h6v3H3v-3zm0 5h6v1a1 1 0 01-1 1H4a1 1 0 01-1-1v-1zm8-4a1 1 0 011-1h9a1 1 0 011 1v6h-2v1a1 1 0 01-1 1h-4a1 1 0 01-1-1v-1h-2v-6z" />
            </svg>
            <span>Tutores</span>
        </button>

        <!-- Botón Tutorados -->
        <button wire:click="cambiarVista('tutorados')" class="flex items-center space-x-1 px-4 py-2 rounded-lg transition-all duration-200
            {{ $vistaActual === 'tutorados' ? 'bg-green-400 text-white' : 'bg-transparent text-black' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path d="M9 5a3 3 0 116 0 3 3 0 01-6 0zm-7 8a4 4 0 014-4h12a4 4 0 014 4v5H2v-5z" />
            </svg>
            <span>Tutorados</span>
        </button>
    </div>

    <div class="space-y-4 mt-6">
        @foreach ($datos as $dato)
            <livewire:info-card :responsable="$dato->nombres . ' ' . $dato->apellidoPaterno . ' ' . $dato->apellidoMaterno"
                :carrera="isset($dato->especialidad) ? $dato->especialidad : ($dato->carrera ?? 'N/A')"
                :estado="isset($dato->estado) ? $dato->estado : 'Estudiante'" :grupo="$dato->grupo->letra ?? 'N/A'"
                tipo="{{ $vistaActual }}" :wire:key="$vistaActual . '-' . $dato->id" />
        @endforeach
    </div>
</div>