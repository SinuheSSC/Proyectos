<div class="flex flex-col items-center h-full w-[90%] sm:w-[80%]">
    <div class="flex items-center justify-center">
        <div class="flex items-center space-x-2 bg-[#86A0FE] text-white px-4 mt-10 py-2 rounded-full shadow-md">
            <img src="{{ $tutorado->fotoPerfil }}" alt="Foto de perfil" class="h-8 w-8 rounded-full">
            <span class="font-semibold text-sm">{{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }}
                {{ $tutorado->apellidoMaterno }}</span>
        </div>
    </div>
    <div class="w-full sm:w-3/4 h-full">
        <div
            class="bg-white  p-4 rounded-md shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
            <div
                class="flex-grow flex flex-col md:flex-row items-center justify-between space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                    {{ $respuestaVida->fechaHitoUno }}
                </span>
            </div>
            <div
                class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-[75%] justify-center">
                {{-- Porcentaje de Actividades --}}
                <div class="text-center">
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-800">
                        {{ $respuestaVida->hitoUno }}
                    </span>
                </div>
            </div>
        </div>
        <div
            class="bg-white  p-4 rounded-md shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
            <div
                class="flex-grow flex flex-col md:flex-row items-center justify-between space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                    {{ $respuestaVida->fechaHitoDos }}
                </span>
            </div>
            <div
                class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-[75%] justify-center">
                {{-- Porcentaje de Actividades --}}
                <div class="text-center">
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-800">
                        {{ $respuestaVida->hitoDos }}
                    </span>
                </div>
            </div>
        </div>
        <div
            class="bg-white  p-4 rounded-md shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
            <div
                class="flex-grow flex flex-col md:flex-row items-center justify-between space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                    {{ $respuestaVida->fechaHitoTres }}
                </span>
            </div>
            <div
                class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-[75%] justify-center">
                {{-- Porcentaje de Actividades --}}
                <div class="text-center">
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-800">
                        {{ $respuestaVida->hitoTres }}
                    </span>
                </div>
            </div>
        </div>
        @if ($respuestaVida->hitoCuatro)
            <div
                class="bg-white  p-4 rounded-md shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
                <div
                    class="flex-grow flex flex-col md:flex-row items-center justify-between space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $respuestaVida->fechaHitoCuatro }}
                    </span>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-[75%] justify-center">
                    {{-- Porcentaje de Actividades --}}
                    <div class="text-center">
                        <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-800">
                            {{ $respuestaVida->hitoCuatro }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
        @if ($respuestaVida->hitoCinco)
            <div
                class="bg-white  p-4 rounded-md shadow-md flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8 my-[1px]">
                <div
                    class="flex-grow flex flex-col md:flex-row items-center justify-between space-y-1 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <span class="font-semibold text-gray-800 text-base text-center md:text-left">
                        {{ $respuestaVida->fechaHitoCinco }}
                    </span>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 ml-0 sm:ml-auto w-full sm:w-[75%] justify-center">
                    {{-- Porcentaje de Actividades --}}
                    <div class="text-center">
                        <span class="inline-block px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-800">
                            {{ $respuestaVida->hitoCinco }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="w-full flex justify-end">
        <button wire:click="goBack"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
            Salir
        </button>
    </div>

</div>
