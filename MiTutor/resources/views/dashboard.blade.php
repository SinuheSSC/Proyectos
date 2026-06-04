<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white shadow-lg rounded-xl p-4 max-w-sm w-90 mx-auto mt-10">
    <div class="text-white px-4 py-1 rounded-full inline-block" style="background-color: #86A0FE;">
        Análisis FODA
    </div>
    <div class="p-4 text-black font-bold text-sm border-b border-gray-300">
        <p>
        El Análisis FODA (Fortalezas, Oportunidades, <br> Debilidades y Amenazas) es una herramienta de <br> autoconocimiento que ayuda a evaluar la <br> situación personal de un estudiante. Se utiliza <br> para identificar puntos fuertes y áreas de mejora, <br> así como factores externos que pueden influir en <br> su desarrollo.
        </p>
    </div>
    <br>
    <div class="flex px-4 pb-2 space-x-3">
        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium text-xs px-2 py-2 w-22 h-10 rounded-lg">
            Volver a realizar
        </button>
        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium text-xs px-2 py-2 w-20 h-10 rounded-lg">
            Resultados
        </button>
    </div>
</div>

</x-app-layout>

<x-modal id="confirmTutorAssignment">
    <div class="p-6 text-center flex flex-col items-center">
        <div class="mb-4">
            <svg width="100" height="100" viewBox="0 0 168 168" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="84" cy="84" r="83" stroke="#E7821E" stroke-width="2" />
                <path d="M89.5588 105.288H78.4708L76.0768 42.036H92.0788L89.5588 105.288ZM75.3208 124.566C75.3208 121.29 76.1608 118.98 77.8408 117.636C79.5208 116.292 81.5788 115.62 84.0148 115.62C86.2828 115.62 88.2568 116.292 89.9368 117.636C91.7008 118.98 92.5828 121.29 92.5828 124.566C92.5828 127.842 91.7008 130.194 89.9368 131.622C88.2568 133.05 86.2828 133.764 84.0148 133.764C81.5788 133.764 79.5208 133.05 77.8408 131.622C76.1608 130.194 75.3208 127.842 75.3208 124.566Z" fill="#E6641E" />
            </svg>
        </div>
        <p class="text-lg font-semibold mb-4 flex-grow">¿Seguro que deseas asignar este tutor?</p>

        <div class="flex w-full justify-end space-x-6 mt-3">
            <button @click="show = false" class="bg-red-500 text-white px-3 py-1 rounded" style="background-color: #f58d8d !important;">
                Cancelar
            </button>
            <button class="bg-green-500 text-white px-3 py-1 rounded" style="background-color: #86de91 !important;">
                Aceptar
            </button>
        </div>
    </div>
</x-modal>

<x-modal id="confirmSuccess">
    <div class="p-6 text-center flex flex-col items-center">
        <div class="mb-6">
            <svg width="100" height="100" viewBox="0 0 168 168" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="84" cy="84" r="82" stroke="#4ACC2A" stroke-width="4" />
                <path d="M42 87L66.4579 118.651" stroke="#4ACC2A" stroke-width="4" stroke-linecap="round" />
                <path d="M125.285 50L67.0621 118.63" stroke="#4ACC2B" stroke-width="4" stroke-linecap="round" />
            </svg>
        </div>
        <p class="text-2xl font-bold text-black whitespace-nowrap">Tutor Asignado Correctamente</p>
    </div>
</x-modal>


<div x-data="{ confirmTutorAssignment: false, confirmSuccess: false }">
    <!-- Botón para abrir el modal de confirmación -->
    <button @click="confirmTutorAssignment = true" class="bg-blue-500 text-white px-4 py-2 rounded">
        Asignar Tutor
    </button>

    <!-- Botón para abrir el modal de éxito -->
    <button @click="confirmSuccess = true" class="bg-green-500 text-white px-4 py-2 rounded">
        Confirmación Exitosa
    </button>
