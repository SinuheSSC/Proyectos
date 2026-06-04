<div class="bg-white rounded-lg shadow-md max-w-md mx-auto w-full sm:max-w-lg md:max-w-xl lg:max-w-2xl">
    <div class="bg-[#FA8072] text-white p-2 rounded-t-lg text-center text-md">
        <h1 class="font-bold">Bienvenido/a {{ $name }}</h1>
    </div>
    <div class="flex mb-2 p-2 items-center justify-center gap-1">
        <p class="text-sm sm:text-base">Seleccione la opción que requiera de acuerdo a sus actividades en:</p>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-[25px] h-[25px] text-gray-800" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </div>
    <div class="bg-[#D9D9D9] px-2 py-1 rounded-e mb-2 w-fit h-fit">
        <h2 class=" text-sm w-fit">Datos generales:</h2>
    </div>
    <div class="p-4">
        <p class="text-xs sm:text-base">RFC: {{ $rfc }}</p>
        <p class="text-xs sm:text-base">Grupos tutorados:
        <div class="flex flex-wrap gap-2 mt-2">
            @foreach ($groups as $group)
                <span class="bg-green-500 text-white px-2 py-1 rounded text-sm sm:text-base">{{ $group }}</span>
            @endforeach
        </div>
        </p>
    </div>
</div>
