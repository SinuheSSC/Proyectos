<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div
            class="relative bg-white p-10 rounded-md shadow-md w-full max-w-md overflow-hidden border-l border-gray-300">
            {{-- Franja lateral derecha de colores --}}
            <div class="absolute top-0 right-0 h-full w-1 flex flex-col">
                <div class="h-1/3 bg-green-400 rounded-tr-md"></div>
                <div class="h-1/3 bg-blue-400"></div>
                <div class="h-1/3 bg-red-400 rounded-br-md"></div>
            </div>

            {{-- Contenido del login --}}
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="MiTutor" class="h-16 mb-2">
            </div>

            @livewire('login-form')

            <div class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:underline">¿Olvidaste tu
                    contraseña?</a>
            </div>

            <div class="text-center mt-2">
                <a href="#" class="text-xs text-gray-400 hover:underline">About us</a>
            </div>
        </div>
    </div>
</x-guest-layout>
