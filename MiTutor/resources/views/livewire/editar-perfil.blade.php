<div>
    <h3 class="text-3xl font-medium">Editar Perfil</h3>
    {{-- INFORMACION DE GENRRAL --}}
    <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">
        <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion general</h4>
        <div class="w-full mt-5  grid grid-cols-[auto_auto_20%] grid-rows-4 gap-8 ">
            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Nombre</label>
                <input  wire:model="data.nombres" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Fecha de nacimiento</label>
                <input wire:model="data.fechaNacimiento" type="date" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="items-center justify-center flex flex-col row-span-4">
                <img class="border-double border-[8px] border-gray-500 p-px w-[200px] h-[200px] rounded-full" src="{{ asset('images/Chad.jpg') }}"/>
                <button class="mt-3 px-4 py-2 rounded-lg text-lg bg-green-200 flex items-center"><img class="w-[35px] mr-2" src="{{ asset('icons/foto-perfil.png') }}"/>Cambiar Foto</button>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Apellido paterno</label>
                <input wire:model="data.apellidoPaterno" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Genero</label>
                <select wire:model="data.genero" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Apellido Materno</label>
                <input wire:model="data.apellidoMaterno" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">CURP</label>
                <input wire:model="data.curp" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
            </div>

            @if($user->RFC)
                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">RFC</label>
                    <input wire:model="data.RFC" type="text" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal"/>
                </div>
            @endif

            @if(isset($user->grupo->letra))
                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">Grupo</label>
                    <select wire:model="data.idGrupo" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->idGrupo }}">{{ $grupo->letra }}</option>
                        @endforeach


                    </select>
                </div>
            @endif

            <div class="flex flex-col justify-center">
                <label class="ml-1 text-md font-medium">Estado civil</label>
                <select wire:model="data.estadoCivil" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
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
    @if ($user->cedulaProfesional)

        <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">

            <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Informacion de profesional</h4>
            <div class="w-full mt-5 grid grid-cols-3 grid-rows-1 gap-8">
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

            </div>
        </div>

        {{-- CONDICIONES --}}
        <div class="mt-5 p-6 bg-white font-medium relative rounded-lg shadow-[0_5px_3px_#909497] overflow-x-clip">

            <h4 class="w-fit px-7 py-1 rounded-full font-medium bg-[#D1FFAE] text-xl">Condiciones</h4>
            <div class="w-full mt-5 grid grid-cols-3 grid-rows-2 gap-8">

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">¿Enfermedad Cronica?</label>
                    <select  wire:model.live="enfermedad"
                            class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>

                    @if($enfermedad === 'Si')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" wire:model="data.enfemerdad" class="py-1 disable px-3 rounded-lg border-gray-400 resize-none text-md font-normal"></textarea>

                    @elseif($enfermedad === 'No')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" class="py-1 disable px-3 rounded-lg bg-gray-200 border-gray-400 resize-none text-md font-normal" disabled></textarea>

                    @endif

                </div>

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">¿Discapacidad?</label>
                    <select wire:model.live="discapcidad" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>


                    @if($discapcidad === 'Si')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" wire:model="data.discapacidadFisica" class="py-1 disable px-3 rounded-lg border-gray-400 resize-none text-md font-normal"></textarea>

                    @elseif($discapcidad === 'No')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" class="py-1 disable px-3 rounded-lg bg-gray-200 border-gray-400 resize-none text-md font-normal" disabled></textarea>

                    @endif

                  </div>

                <div class="flex flex-col justify-center">
                    <label class="ml-1 text-md font-medium">¿Condicion Psicologica?</label>
                    <select wire:model.live="psicologica" class="py-1 px-3 rounded-lg border-gray-400 text-md font-normal">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>

                    @if($psicologica === 'Si')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" wire:model="data.situacionPsicologica" class="py-1 disable px-3 rounded-lg border-gray-400 resize-none text-md font-normal"></textarea>

                    @elseif($psicologica === 'No')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" class="py-1 disable px-3 rounded-lg bg-gray-200 border-gray-400 resize-none text-md font-normal" disabled></textarea>

                    @endif

                </div>


                <div class="flex flex-col justify-center col-span-3">
                    <label class="ml-1 text-md font-medium">Necesidad Especial</label>
                    <select wire:model.live="necesidadEsp" class="w-fit py-1 px-3 pr-20 rounded-lg border-gray-400 text-md font-normal">
                        <option value="Si">Si</option>
                        <option value="No">No</option>
                    </select>

                    @if($necesidadEsp === 'Si')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" wire:model="data.necesidadEspecial" class="py-1 disable px-3 rounded-lg border-gray-400 resize-none text-md font-normal"></textarea>

                    @elseif($necesidadEsp === 'No')

                        <label class="ml-1 mt-2 text-md font-medium">Descripcion</label>
                        <textarea rows="5" class="py-1 disable px-3 rounded-lg bg-gray-200 border-gray-400 resize-none text-md font-normal" disabled></textarea>

                    @endif

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
