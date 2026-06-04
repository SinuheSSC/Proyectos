<div class="min-h-screen w-full flex flex-col items-center justify-start py-8 px-4">
    <!-- Encabezado FODA -->
    <div class="flex items-center space-x-2 bg-[#86A0FE] text-white px-6 py-3 rounded-full shadow-md mb-8">
        <h2 class="text-xl font-semibold">Análisis FODA</h2>
    </div>

    <!-- Contenedor principal -->
    <div class="w-full max-w-5xl flex flex-col items-center">
        <!-- Versión móvil del círculo FODA -->
        <div class="block md:hidden w-56 h-56 bg-teal-600 rounded-full flex flex-col items-center justify-center shadow-xl mb-10">
            <div class="grid grid-cols-2 gap-0 w-full h-full text-white text-center font-bold text-3xl">
                <div class="flex items-center justify-center relative border-r border-b border-teal-500 p-2">
                    F
                    <span class="absolute bottom-2 text-xs font-normal">Fortalezas</span>
                </div>
                <div class="flex items-center justify-center relative border-b border-teal-500 p-2">
                    A
                    <span class="absolute bottom-2 text-xs font-normal">Amenazas</span>
                </div>
                <div class="flex items-center justify-center relative border-r border-teal-500 p-2">
                    <span class="absolute top-2 text-xs font-normal">Oportunidades</span>
                    O
                </div>
                <div class="flex items-center justify-center relative p-2">
                    <span class="absolute top-2 text-xs font-normal">Debilidades</span>
                    D
                </div>
            </div>
        </div>

        <!-- Diagrama FODA para desktop -->
        <div class="hidden md:block relative w-full">
            <!-- Círculo central más pequeño -->
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 w-48 h-48 bg-teal-600 rounded-full shadow-2xl">
                <div class="grid grid-cols-2 gap-0 w-full h-full text-white text-center font-bold text-3xl">
                    <div class="flex items-center justify-center relative border-r border-b border-teal-500 p-2">
                        F
                        <span class="absolute bottom-2 text-xs font-normal">Fortalezas</span>
                    </div>
                    <div class="flex items-center justify-center relative border-b border-teal-500 p-2">
                        A
                        <span class="absolute bottom-2 text-xs font-normal">Amenazas</span>
                    </div>
                    <div class="flex items-center justify-center relative border-r border-teal-500 p-2">
                        <span class="absolute top-2 text-xs font-normal">Oportunidades</span>
                        O
                    </div>
                    <div class="flex items-center justify-center relative p-2">
                        <span class="absolute top-2 text-xs font-normal">Debilidades</span>
                        D
                    </div>
                </div>
            </div>

            <!-- Textareas posicionados alrededor del círculo -->
            <!-- Fortalezas - Arriba Izquierda -->
            <div class="absolute top-0 left-0 w-64 p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-base mb-3">Escribe Aquí Tus Fortalezas</h3>
                <textarea
                    wire:model.live="fortaleza"
                    class="w-full h-24 p-3 text-sm text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus fortalezas aquí...">
                </textarea>
                @error('fortaleza')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Amenazas - Arriba Derecha -->
            <div class="absolute top-0 right-0 w-64 p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-base mb-3">Escribe Aquí Tus Amenazas</h3>
                <textarea
                    wire:model.live="amenazas"
                    class="w-full h-24 p-3 text-sm text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus amenazas aquí...">
                </textarea>
                @error('amenazas')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Oportunidades - Abajo Izquierda -->
            <div class="absolute bottom-0 left-0 w-64 p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-base mb-3">Escribe Aquí Tus Oportunidades</h3>
                <textarea
                    wire:model.live="oportunidad"
                    class="w-full h-24 p-3 text-sm text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus oportunidades aquí...">
                </textarea>
                @error('oportunidad')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Debilidades - Abajo Derecha -->
            <div class="absolute bottom-0 right-0 w-64 p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-base mb-3">Escribe Aquí Tus Debilidades</h3>
                <textarea
                    wire:model.live="debilidad"
                    class="w-full h-24 p-3 text-sm text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus debilidades aquí...">
                </textarea>
                @error('debilidad')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Espaciador para dar altura al contenedor relativo -->
            <div class="w-full h-80"></div>
        </div>

        <!-- Versión móvil de textareas -->
        <div class="block md:hidden w-full space-y-6">
            <!-- Fortalezas -->
            <div class="p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Escribe Aquí Tus Fortalezas</h3>
                <textarea
                    wire:model.live="fortaleza"
                    class="w-full h-32 p-4 text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus fortalezas aquí...">
                </textarea>
                @error('fortaleza')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Amenazas -->
            <div class="p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Escribe Aquí Tus Amenazas</h3>
                <textarea
                    wire:model.live="amenazas"
                    class="w-full h-32 p-4 text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus amenazas aquí...">
                </textarea>
                @error('amenazas')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Oportunidades -->
            <div class="p-4 rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Escribe Aquí Tus Oportunidades</h3>
                <textarea
                    wire:model.live="oportunidad"
                    class="w-full h-32 p-4 text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus oportunidades aquí...">
                </textarea>
                @error('oportunidad')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Debilidades -->
            <div class="p-4 mt-[100px] rounded-xl shadow-lg bg-white">
                <h3 class="font-bold text-gray-800 text-lg mb-4">Escribe Aquí Tus Debilidades</h3>
                <textarea
                    wire:model.live="debilidad"
                    class="w-full h-32 p-4 text-gray-700 bg-white border-2 border-gray-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Escribe tus debilidades aquí...">
                </textarea>
                @error('debilidad')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center w-full px-4">
            <button wire:click="guardarFoda"
                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow-md transition duration-200 text-lg font-medium">
                {{ $fodaExistente ? 'Actualizar FODA' : 'Guardar FODA' }}
            </button>

            <button wire:click="limpiarCampos"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-lg shadow-md transition duration-200 text-lg font-medium">
                Limpiar
            </button>

            <a href="/cuaderno-actividades" class="w-full sm:w-auto">
                <button wire:click="goBack"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg shadow-md transition duration-200 w-full text-lg font-medium">
                    Salir
                </button>
            </a>
        </div>
    </div>

    <!-- Mensajes flash -->
    @if (session()->has('info'))
        <div class="fixed top-6 right-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-lg z-50 max-w-xs">
            {{ session('info') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-6 right-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-lg z-50 max-w-xs">
            {{ session('error') }}
        </div>
    @endif
</div>
