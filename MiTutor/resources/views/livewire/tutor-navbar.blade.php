<nav
    class="bg-gray-50 shadow-md py-3 px-6 flex items-center justify-between fixed top-0 left-20 right-0 z-10
    min-h-[70px]">
    {{-- Added min-h-[70px] for consistent height --}}
    <div class="flex items-center">
        <img src="{{ asset('images/tutor/logo-tutor.png') }}" alt="MiTutor Logo" class="h-8">
    </div>

    {{-- Contenedor principal del avatar y el dropdown --}}
    <div class="relative flex items-center bg-red-400 text-white rounded-full px-4 py-2 cursor-pointer hover:bg-red-500 transition-colors duration-200
        hidden sm:flex" {{-- Added: hidden por defecto, flex a partir de 'sm' --}}
        wire:click="toggleDropdown"> {{-- Usamos wire:click para alternar el dropdown --}}
        <img src="{{ $userAvatar }}" onerror="this.src='{{ asset('/images/tutor/default.webp') }}'" alt="User Avatar" class="h-8 w-8 rounded-full mr-2">
        <span class="font-semibold text-sm">{{ $userName }}</span>
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>

        {{-- Dropdown de Cerrar Sesión --}}
        <div @class([
            'absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-30',
            'hidden' => !$isDropdownOpen, // Oculto si $isDropdownOpen es false
            'block' => $isDropdownOpen, // Visible si $isDropdownOpen es true
            'transition-all duration-300 ease-in-out',
        ]) style="top: 100%;">
            <a href="{{ route('tutor.info', ['curp' => session('curp')]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                Mi información
            </a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                wire:click.prevent="logout">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                </svg>
                Cerrar Sesión
            </a>
        </div>
    </div>
</nav>
