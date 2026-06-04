<div>
    @if(session()->has('succesStore'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 8000)"
            class="fixed z-50 bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg text-3xl shadow-lg transition-opacity duration-300"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            {{ session('succesStore') }}
        </div>
    @endif

    <h3 class="text-3xl font-medium">Agregar Usuario</h3>
    {{-- INFORMACION DE GENRRAL --}}
    <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">
        <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion general</h4>
        <div class="w-full mt-5  grid grid-cols-[auto_auto_20%] grid-rows-4 gap-8 ">

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Tipo de cuenta</label>
                <select wire:model.live="tipoCuenta" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal" >
                    <option value="Estudiante">Tutorado</option>
                    <option value="Profesor">Tutor</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium" >CURP</label>
                <input wire:model="data.curp" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="items-center justify-center flex flex-col row-span-4">
                <img class="border-double border-[8px] border-gray-500 p-px w-[200px] h-[200px] rounded-full" src="{{ asset('images/Chad.jpg') }}"/>
                <button class="mt-3 px-4 py-2 rounded-lg text-lg bg-green-200 flex items-center"><img class="w-[35px] mr-2" src="{{ asset('icons/foto-perfil.png') }}"/>Cambiar Foto</button>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Nombre</label>
                <input  wire:model="data.nombres" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Fecha de nacimiento</label>
                <input wire:model="data.fechaNacimiento" type="date" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>



            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Apellido paterno</label>
                <input wire:model="data.apellidoPaterno" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Genero</label>
                <select wire:model="data.genero" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                    <option value="">---SELECCIONA GENERO---</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Apellido Materno</label>
                <input wire:model="data.apellidoMaterno" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Estado civil</label>
                <select wire:model="data.estadoCivil" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                    <option value="">---SELECCIONA ESTADO CIVIL---</option>
                    <option value="Soltero/a">Soltero/a</option>
                    <option value="Casado/a">Casado/a</option>
                    <option value="Viudo/a">Viudo/a</option>
                </select>
            </div>

        </div>
    </div>

    {{-- INFORMACION DE CONTACTO --}}
    <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">
        <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion de contacto</h4>
        <div class="w-full mt-5 grid grid-cols-2 grid-rows-3 gap-8">

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Telefono</label>
                <input wire:model="data.telefono" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Email</label>
                <input wire:model="data.email" type="email" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Codigo Postal</label>
                <input wire:model="data.codigoPostal" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>


            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Estado</label>
                <input wire:model="data.estado" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>


            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Municipio</label>
                <input wire:model="data.municipio" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Calle y numero</label>
                <input wire:model="data.calleYnumero" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

        </div>
    </div>


    {{-- INFORMACION DE PROFESIONAL SI ES TUTOR --}}
    @if ($tipoCuenta === 'Profesor' || $tipoCuenta === 'Admin')

        <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">

            <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion de profesional</h4>
            <div class="w-full mt-5 grid grid-cols-2 grid-rows-2 gap-8">
                <div class="flex flex-col justify-center">
                        <label class="ml-1 text-md font-medium">RFC</label>
                        <input wire:model="data.RFC" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Cedula</label>
                    <input wire:model="data.cedulaProfesional" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Titulo</label>
                    <input wire:model="data.titulo" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Especialidad</label>
                    <input wire:model="data.especialidad" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>

            </div>
        </div>

    @else
    {{-- INFORMACION DE TUTORADO --}}
        <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">
            {{-- INFORMACION ASPIRACIONAL --}}
            <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion aspiracional</h4>
            <div class="w-full mt-5 grid grid-cols-1 grid-rows-[fit_fit] gap-0">

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Carrera</label>
                    <input wire:model="data.carrera" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>

                <div class="flex mt-3 flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Razones para la carrera</label>

                    <textarea rows="5" wire:model="data.razonesCarrera" class="py-1 px-3 rounded-lg border-gray-400 resize-none text-md font-normal"></textarea>
                </div>

                <div class="flex flex-col mt-5 justify-center">
                    <label class="ml-1 text-md font-medium">Asignar a un grupo </label>
                    <select wire:model="data.idGrupo" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                            <option value="">---SELECCIONA GRUPO---</option>
                        @foreach ($grupos as $grupo)

                            <option value="{{ $grupo->idGrupo }}">{{ $grupo->letra }}</option>
                        @endforeach


                    </select>
                    <span class="text-md italic font-normal">Este grupo es independiente de la carrera deseada*</span>
                </div>

            </div>
        </div>


    @endif

    <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">

            <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Contraseña de acceso</h4>
            <div class="w-full mt-5 grid grid-cols-2 grid-rows-1 gap-8">


                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium italic">Esta sera la contraseña con la que el tutor/turorado podra acceder a su cuenta*</label>
                    <input wire:model="data.contrasena" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>
            </div>
    </div>
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="p-10 pb-20 flex w-full justify-end">

        <button wire:click="guardar()" class="flex items-center ml-10 px-10 py-4 shadow-[0px_1px_5px_#a3be8c] text-xl font-medium rounded-lg bg-[#86DE91]">
            <img class="w-[18px] h-[18px] mr-4" src="{{ asset('icons/guardar-el-archivo.png') }}"/>
            Guardar
        </button>

        <button class="ml-10 px-10 py-4 shadow-[0px_1px_5px_#a3be8c] text-xl font-medium rounded-lg bg-gray-400">
            Cancelar
        </button>
    </div>

</div>
