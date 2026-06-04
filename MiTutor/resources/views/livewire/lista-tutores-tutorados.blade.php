<div class="m-10 flex flex-col w-[90%] relative">

    <div class="flex justify-between">
        <div class="w-[400px] p-1 text-xl font-medium bg-[#D9D9D9] flex relative rounded-lg justify-evenly items-center cursor-pointer shadow-xl">
            <button wire:click="setTutores(false)" class="z-20 w-[45%] py-3 flex items-center justify-center rounded-lg "
                    :class="{ 'bg-[#86DE91]': @json($tutores === false), 'hover:bg-[#ecfcee]': @json($tutores === true) }">
                        <img class="w-[26px] mr-2" src="{{ asset('icons/manzana.png') }}"/>Tutores
            </button>
            <button wire:click="setTutores(true)" class="z-20 w-[45%] py-3 flex items-center justify-center rounded-lg "
                    :class="{ 'bg-[#86DE91]': @json($tutores === true), 'hover:bg-[#ecfcee]': @json($tutores === false) }">
                        <img class="w-[26px] mr-2" src="{{ asset('icons/gorro-de-graduacion.png') }}"/>Tutorados
            </button>
        </div>
        <input type="search" wire:model.live='search' placeholder="🔎 Buscar..." class="rounded border-2 mx-4 border-gray-300 w-1/3">

        <button wire:click="showModalGrupo" class="ml-10 px-5  shadow-[0px_1px_5px_#a3be8c] text-xl font-medium rounded-lg bg-[#86DE91]">
            Crear Grupo
        </button>
    </div>

    @foreach ($data as $element)
        <div class="mt-5 h-[80px] items-center flex px-6 bg-[#F9F9F9] font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip group/select">
            <span class="w-2 bg-[#86DE91] left-0 top-0 absolute h-full rounded-l-lg transition-transform duration-300 transform -translate-x-2  group-hover/select:translate-x-0"></span>
            <div class="grid grid-cols-[35%_32%_20%] grid-rows-2 gap-3  w-full">
                <span class="truncate">Nombre: {{ $element->nombres ." ".$element->apellidoPaterno ." ".$element->apellidoMaterno}}</span>
                <span class="truncate">Email: {{ $element->email }}</span>
                <span>Telefono: {{ $element->telefono }}</span>
                @if(!$tutores)
                    <span class="truncate">Especialidad: {{ $element->especialidad }}</span>
                @else
                    <span class="truncate">Necesidad Especial: {{ $element->necesidadEspecial }}</span>
                    <span>Carrera: {{ $element->carrera }}</span>
                    <span>Grupo: {{ $element->grupo->letra }} </span>
                @endif


            </div>
            <div class="relative flex justify-center items-center group/div">
                <img class="w-[26px] cursor-pointer " src="{{ asset('icons/menu-de-puntos.png') }}"/>
                <ul class="z-30 hidden py-3 px-5 top-0 right-0 w-[225px] bg-white rounded-lg shadow-[3px_3px_10px_#909497] absolute group-hover/div:block">
                    <li wire:click='showModal("{{ $element->curp }}")' class="text-nowrap py-1 flex items-center rounded-lg hover:bg-[#ecfcee] cursor-pointer"><img class="w-[40px] px-2" src="{{ asset('icons/wachar-perfil.png') }}"/>Ver perfil</li>
                    <li wire:click='editarPerfil("{{ Crypt::encrypt($element->curp) }}")' class="text-nowrap py-1 flex items-center rounded-lg hover:bg-[#ecfcee] cursor-pointer"><img class="w-[40px] px-2" src="{{ asset('icons/editar-perfil.png') }}"/>Editar perfil</li>
                    @if($tutores)
                        <li wire:click="showModalAginacion('{{ $element->idCuentaTutorado }}')" class="text-nowrap py-1 flex items-center rounded-lg hover:bg-[#ecfcee] cursor-pointer"><img class="w-[40px] px-2" src="{{ asset('icons/morros.png') }}"/> Asignar grupo</li>
                    @endif


                </u>
            </div>
        </div>
    @endforeach

<!--ASIGNAR GRUPO-->
    <div class="{{ $modalStatusAsignacion }}">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-25 flex justify-center items-center z-50">
            <div class=" relative flex flex-col items-center rounded-lg shadow-[0_5px_3px_#909497] bg-[#F9F9F9] font-medium p-4 w-1/2">
                <img wire:click="closeModal" class="z-50 w-[34px] bg-gray-300 p-2 rounded-full absolute -top-3 -right-3 cursor-pointer " src="{{ asset('icons/cerrar.png') }}"/>
                <div class="px-7 py-3 w-full flex flex-col items-center relative bg-white rounded-lg border shadow-[0_2px_5px_#909497] ">

                    <h2 class=" mb-3 bg-[#86DE91] text-xl rounded-xl px-5 px-3 w-fit">Asignar Grupo</h2>
                    <label>Seleccionar Grupo:</label>
                    <select wire:model.live="asignacion" class="rounded-md">
                        @foreach($grupos_all as $grupo)
                            <option value="{{ $grupo->idGrupo }}"> {{ $grupo->letra." : ".$grupo->tutor->nombres ." ". $grupo->tutor->apellidoPaterno }} </option>
                        @endforeach
                    </select>


                    <button wire:click="asignacionGrupo" class="mt-5 py-2 px-3 font-medium rounded-lg bg-[#86DE91]">
                        Asignar Grupo
                    </button>
                </div>
            </div>
        </div>
    </div>

 <!--CREAR GRUPO-->
    <div class="{{ $modalStatusGrupo }}">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-25 flex justify-center items-center z-50">
            <div class=" relative flex flex-col items-center rounded-lg shadow-[0_5px_3px_#909497] bg-[#F9F9F9] font-medium p-4 w-fit">
                <img wire:click="closeModal" class="z-50 w-[34px] bg-gray-300 p-2 rounded-full absolute -top-3 -right-3 cursor-pointer " src="{{ asset('icons/cerrar.png') }}"/>
                <div class="px-7 py-3 w-full flex flex-col items-center relative bg-white rounded-lg border shadow-[0_2px_5px_#909497] ">

                    <h2 class=" mb-3 bg-[#86DE91] text-xl rounded-xl px-5 px-3 w-fit">Nuevo Grupo</h2>
                    <label>Seleccionar profesor:</label>
                    <select wire:model.live="tutorNuevoGrupo" class="rounded-md">
                        @foreach($tutores_all as $tutor)
                            <option value="{{ $tutor->idCuentaTutor }}"> {{ $tutor->nombres." ".$tutor->apellidoPaterno }} </option>
                        @endforeach
                    </select>

                    <label>Letra:</label>
                    <input  wire:model.live="letra" class="rounded-lg" type="text"/>
                    <button wire:click="nuevoGrupo" class="mt-5 py-2 px-3 font-medium rounded-lg bg-[#86DE91]">
                        Crear Grupo
                    </button>
                </div>
            </div>
        </div>
    </div>


<!--MODAL VER PERFIl-->
    <div class="{{ $modalStatus }}">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-25 flex justify-center items-center z-50">
            <div class=" relative flex flex-col items-center rounded-lg shadow-[0_5px_3px_#909497] bg-[#F9F9F9] font-medium p-4 w-2/3">
                <img wire:click="closeModal" class="z-50 w-[34px] bg-gray-300 p-2 rounded-full absolute -top-3 -right-3 cursor-pointer " src="{{ asset('icons/cerrar.png') }}"/>
                    <div class="px-7 py-3 w-full relative bg-white rounded-lg border shadow-[0_2px_5px_#909497] ">
                @if (isset($usuario))
                        <h2 class=" mb-3 bg-[#86DE91] text-xl rounded-xl px-5 px-3 w-fit">Informacion General</h2>
                        <img class="absolute w-[225px] h-[225px] rounded-full border-2 border-black top-[5%]  right-[10%]" src="{{ $usuario->fotoPerfil }}"/>
                        <p class="my-1 mx-6">Nombre: {{ $usuario->nombres ." ".$usuario->apellidoPaterno." ".$usuario->apellidoMaterno}}</p>
                        <p class="my-1 mx-6">Fecha de nacimiento: {{ $usuario->fechaNacimiento }}</p>
                        <p class="my-1 mx-6">Genero: {{ $usuario->genero }}</p>
                        <p class="my-1 mx-6">Estado Civil: {{ $usuario->estadoCivil }}</p>
                        <p class="my-1 mx-6">CURP: {{ $usuario->curp }}</p>
                        @if(!$tutores)
                            <p class="my-1 mx-6">RFC: {{ $usuario-> RFC}}</p>
                            <p class="my-1 mx-6">Cedula profesional: {{ $usuario->cedulaProfesional }}</p>
                            <p class="my-1 mx-6">Especialidad: {{ $usuario->especialidad }}</p>
                            <p class="my-1 mx-6">Titulo: {{ $usuario->titulo }}</p>
                        @else
                            <p class="my-1 mx-6">Carrera: {{ $usuario-> carrera}}</p>
                            <p class="my-1 mx-6">Canalizacion: {{ $usuario->canalizacion }}</p>
                            <p class="my-1 mx-6">Necesidad especial: {{ $usuario->necesidadEspecial }}</p>

                        @endif
                    </div>

                    <div class="mt-3 px-7 py-3 w-full relative bg-white rounded-lg border shadow-[0_2px_5px_#909497] ">
                        <h2 class="bg-[#86DE91] text-xl rounded-xl px-5 px-3 w-fit">Datos de contacto</h2>
                        <div class="mt-3 grid grid-cols-[35%_55%] grid-rows-3 gap-2 w-full">
                            <p class="my-px mx-6 truncate">Telefono: {{ $usuario->telefono }}</p>
                            <p class="my-px mx-6 truncate">Email: {{ $usuario->email }}</p>
                            <p class="my-px mx-6 truncate">Estado: {{ $usuario->estado }}</p>
                            <p class="my-px mx-6 truncate">Calle y numero: {{ $usuario->calleYnumero }}</p>
                            <p class="my-px mx-6 truncate">Codigo postal: {{ $usuario->codigoPostal }}</p>
                            <p class="my-px mx-6 truncate">Municipio: {{ $usuario->municipio }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
