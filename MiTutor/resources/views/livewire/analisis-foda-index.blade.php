<div class="h-fit w-fit flex flex-col items-center justify-center">
    <div class="flex items-center space-x-2 bg-[#86A0FE] text-white px-4 mt-10 py-2 rounded-full shadow-md">
        <img src="{{$tutorado->fotoPerfil}}" alt="Foto de perfil" class="h-8 w-8 rounded-full">
        <span class="font-semibold text-sm">{{$tutorado->nombres}} {{$tutorado->apellidoPaterno}} {{$tutorado->apellidoMaterno}}</span>
    </div>
    <div class="flex flex-col items-center justify-center px-4 h-full">
        {{-- Círculo Central para Mobile (visible solo en pantallas pequeñas) --}}
        <div
            class="block md:hidden w-64 h-64 bg-teal-600 rounded-full flex flex-col items-center justify-center shadow-xl mb-8 mx-auto">
            <div class="grid grid-cols-2 gap-0 w-full h-full text-white text-center font-bold text-4xl">
                <div class="flex items-center justify-center relative border-r border-b border-teal-500 p-2">
                    F
                    <span class="absolute bottom-2 text-sm font-normal">Fortalezas</span>
                </div>
                <div class="flex items-center justify-center relative border-b border-teal-500 p-2">
                    A
                    <span class="absolute bottom-2 text-sm font-normal">Amenazas</span>
                </div>
                <div class="flex items-center justify-center relative border-r border-teal-500 p-2">
                    <span class="absolute top-2 text-sm font-normal">Oportunidades</span>
                    O
                </div>
                <div class="flex items-center justify-center relative p-2">
                    <span class="absolute top-2 text-sm font-normal">Debilidades</span>
                    D
                </div>
            </div>
        </div>

        {{-- Contenedor principal del diagrama FODA --}}
        <div
            class="flex flex-col items-center md:grid md:grid-cols-2 gap-x-8 gap-y-6 max-w-6xl w-full mx-auto relative p-4">

            {{-- Nube Superior Izquierda: Fortalezas --}}
            <div
                class="p-4 rounded-lg shadow-md bg-white text-left h-48 overflow-y-auto w-full max-w-sm md:w-auto mb-4 md:mb-0 md:col-span-1 md:mr-36">
                <h3 class="font-bold text-gray-800 mb-2">Fortalezas</h3>
                <ul class="list-none text-gray-700">
                    <li class="font-semibold mt-2 text-sm">
                        {{ $foda->fortaleza }}
                    </li>
                </ul>
            </div>

            {{-- Nube Superior Derecha: Amenazas --}}
            <div
                class="p-4 rounded-lg shadow-md bg-white text-left h-48 overflow-y-auto w-full max-w-sm md:w-auto mb-4 md:mb-0 md:col-span-1 md:ml-36">
                <h3 class="font-bold text-gray-800 mb-2">Amenazas</h3>
                <ul class="list-none text-gray-700">
                    <li class="font-semibold mt-2 text-sm">
                        {{ $foda->amenazas }}
                    </li>
                </ul>
            </div>

            {{-- Círculo Central FODA para Desktop (visible solo en pantallas medianas y grandes) --}}
            <div
                class="hidden md:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-64 h-64 md:w-80 md:h-80 bg-teal-600 rounded-full flex flex-col items-center justify-center shadow-xl">
                <div class="grid grid-cols-2 gap-0 w-full h-full text-white text-center font-bold text-4xl">
                    <div class="flex items-center justify-center relative border-r border-b border-teal-500 p-2">
                        F
                        <span class="absolute bottom-2 text-sm font-normal">Fortalezas</span>
                    </div>
                    <div class="flex items-center justify-center relative border-b border-teal-500 p-2">
                        A
                        <span class="absolute bottom-2 text-sm font-normal">Amenazas</span>
                    </div>
                    <div class="flex items-center justify-center relative border-r border-teal-500 p-2">
                        <span class="absolute top-2 text-sm font-normal">Oportunidades</span>
                        O
                    </div>
                    <div class="flex items-center justify-center relative p-2">
                        <span class="absolute top-2 text-sm font-normal">Debilidades</span>
                        D
                    </div>
                </div>
            </div>

            {{-- Nube Inferior Izquierda: Oportunidades --}}
            <div
                class="p-4 rounded-lg shadow-md bg-white text-left h-48 overflow-y-auto w-full max-w-sm md:w-auto mb-4 md:mb-0 md:col-span-1 md:mr-36">
                <h3 class="font-bold text-gray-800 mb-2">Oportunidades</h3>
                <ul class="list-none text-gray-700">
                    <li class="font-semibold mt-2 text-sm">
                        {{ $foda->oportunidad }}
                    </li>
                </ul>
            </div>

            {{-- Nube Inferior Derecha: Debilidades --}}
            <div
                class="p-4 rounded-lg shadow-md bg-white text-left h-48 overflow-y-auto w-full max-w-sm md:w-auto md:col-span-1 md:ml-36">
                <h3 class="font-bold text-gray-800 mb-2">Debilidades</h3>
                <ul class="list-none text-gray-700">
                    <li class="font-semibold mt-2 text-sm">
                        {{ $foda->debilidad }}
                    </li>
                </ul>
            </div>
        </div>

        {{-- Botones Inferiores - Fluyen con el contenido y se centran --}}
        <div class="mt-8 flex space-x-4 justify-center w-full max-w-6xl">
            <button wire:click="goBack"
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
                Salir
            </button>
        </div>

        {{-- Mensajes flash (opcional) --}}
        @if (session()->has('info'))
            <div class="absolute top-4 right-4 p-3 bg-blue-100 text-blue-700 rounded-md shadow-md">
                {{ session('info') }}
            </div>
        @endif
    </div>
</div>
