<div>
    @if(session()->has('succesStore'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg text-3xl shadow-lg transition-opacity duration-300"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            {{ session('succesStore') }}
        </div>
    @endif

    {{-- CALENDARIO BY DEEPSEEK --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header del calendario -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $currentDate->translatedFormat('F Y') }}
                </h2>
                <button wire:click="showAgregarCita" class="text-md font-medium ml-5 px-4 py-2 bg-green-500 text-white hover:bg-green-400 rounded border-1 border-green-600">
                    Agregar Cita
                </button>
            </div>
            <div class="flex space-x-2">
                <button wire:click="previousMonth" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button wire:click="nextMonth" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Días de la semana -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $day)
            <div class="bg-gray-100 py-2 text-center text-sm font-medium text-gray-600">
                {{ $day }}
            </div>
            @endforeach
        </div>

        <!-- Días del mes -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            @foreach($weeks as $week)
                @foreach($week as $day)
                    <div class="bg-white min-h-32 p-1 @if(!$day['isCurrentMonth']) bg-gray-50 @endif">
                        <div class="flex flex-col h-full">
                            <div class="flex justify-between items-center mb-1">
                                <span class="@if($day['isToday']) bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center @elseif(!$day['isCurrentMonth']) text-gray-400 @endif text-sm font-medium">
                                    {{ $day['date']->day }}
                                </span>
                            </div>

                            <!-- Eventos/Citas -->
                            <div class="flex-grow overflow-y-auto max-h-24">
                                @foreach($day['events'] as $event)
                                    <button wire:click="verMasCita({{ $event }})"
                                             class="text-xs text-left w-full p-1 mb-1 rounded bg-blue-100 text-blue-800 truncate">

                                        <span class="font-medium">

                                            {{ $event['canalizacion'] }}
                                            {{ $event['tutorado']['nombres'] }}

                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- MODAL A DESPLEGAR --}}
    @if($showModal)
        <div  class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Fondo oscuro -->
            <div wire:click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- Contenedor principal -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <!-- Contenido del modal (75% de ancho) -->
                <div class="bg-white rounded-lg shadow-xl transform transition-all w-3/4 max-w-6xl">
                    <!-- Encabezado -->
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Detalles de la Cita
                        </h3>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="p-6">
                        <!-- Información de la cita -->
                        <div class="grid grid-cols-[18%_18%_auto_auto] gap-2 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Fecha:</p>
                                @if($dataModal['citaFutura'])
                                    <p class="text-lg">{{ $dataModal['fechaCita'] }}</p>
                                @else
                                    <input wire:model="dataModal.fechaCita" type="date" class="p-0 w-[135px] border-none text-lg" />
                                @endif
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Hora:</p>

                                @if($dataModal['citaFutura'])
                                    <p class="text-lg">{{ $dataModal['hora']  }}</p>
                                @else
                                    <input wire:model="dataModal.hora" type="time" class="p-0 w-[135px] border-none text-lg"/>
                                @endif

                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Canalizacion:</p>

                                @if($dataModal['citaFutura'])
                                    <p class="text-lg capitalize">{{ $dataModal['canalizacion'] }}</p></p>
                                @else
                                    <select wire:model="dataModal.canalizacion" class="p-0 w-[150px] border-none text-lg">
                                        <option value="Enfermeria">Enfermeria</option>
                                        <option value="Psicologia">Psicologia</option>
                                    </select>
                                @endif

                            </div>

                            @if(session()->has('error'))
                                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
                                   {{ session('error') }}
                                </div>
                            @endif
                        </div>

                        <!-- Información del tutor -->
                        <div class="border-t pt-4 mb-6">
                            <h4 class="font-semibold text-lg mb-3">Datos del Tutorado</h4>
                            <div class="grid grid-cols-[35%_10%_auto] gap-2">
                                <div class="">
                                    <p class="text-gray-500">Nombre:</p>
                                    <p>{{ $dataModal['nombre'] }}<p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Grupo:</p>
                                    <p>{{ $dataModal['grupo'] }}</p>
                                </div>

                                <div>
                                    <p class="text-gray-500">Tutor:</p>
                                    <p>{{ $dataModal['tutor'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de edición -->
                        <div class="grid grid-cols-3 gap-x-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dispacidad</label>
                                <textarea rows="5" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" readonly>{{ $dataModal['discapacidadFisica'] }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Enfermedad</label>
                                <textarea rows="5" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" readonly>{{ $dataModal['enfemerdad'] }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Condicion Psicologica</label>
                                <textarea rows="5" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" readonly>{{ $dataModal['situacionPsicologica'] }}</textarea>
                            </div>
                        </div>

                        @if ($dataModal['citaFutura'])
                            <div class="mt-3 flex justify-center ">
                                <div class="w-[100%]">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Resultados de la cita</label>
                                    <textarea wire:model="dataModal.resultados" rows="5" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                                </div>
                            </div>

                            <div class="mt-3 flex justify-center ">
                                <div class="w-[100%]">
                                    <div class="items-center flex">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">¿Aplica necesidad especial?</label>
                                        <select wire:model.live="modalNecesidadEspecial" class="ml-3 w-[72px] text-sm border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="No">No</option>
                                            <option value="Si">Si</option>
                                        </select>
                                    </div>

                                    <label class="block text-sm font-medium text-gray-700 mb-1">Describa</label>
                                    @if($modalNecesidadEspecial === 'No')
                                        <textarea rows="5" class="w-full border bg-gray-100 border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none" disabled></textarea>
                                    @else
                                         <textarea wire:model="dataModal.necesidadEspecial" rows="5" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Pie del modal -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-between space-x-3">
                        <button wire:click="borrarCita" wire:confirm="¿Estás seguro? La cita se borrara y esta accion no se puede deshacer" type="button" class="float-left px-4 py-2 border border-red-300 rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Borrar Cita
                        </button>
                        <div class="flex">
                            <button wire:click="closeModal()" type="button" class="mr-3 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </button>
                            <button wire:click="guardarCambios" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Guardar Cambios
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL AGREGAR CITA --}}
    @if($addCita)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Fondo oscuro -->
            <div wire:click="closeModal()" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- Contenedor principal -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <!-- Contenido del modal (50% de ancho) -->
                <div class="bg-white rounded-lg shadow-xl transform transition-all w-3/5 max-w-6xl">
                    <!-- Encabezado -->
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Agregar cita
                        </h3>
                    </div>

                    <!-- Cuerpo del modal -->
                    <div class="p-6">
                        <!-- Información de la cita -->
                        <div class="grid grid-cols-4 gap-2 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">Fecha:</p>
                                <input wire:model="dataModalNew.fechaNew" type="date" class="p-0 w-[135px] border-none text-lg" />
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Hora:</p>
                                <input wire:model="dataModalNew.horaNew" type="time" class="p-0 w-[135px] border-none text-lg"/>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Canalizacion:</p>
                                    <select wire:model="dataModalNew.canalizacionNew" class="p-0 w-[150px] border-none text-lg">
                                        <option value="Enfermeria">Enfermeria</option>
                                        <option value="Psicologia">Psicologia</option>
                                    </select>
                            </div>

                            @if(session()->has('error'))
                                <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
                                   {{ session('error') }}
                                </div>
                            @endif
                        </div>

                        <!-- Formulario de edición -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-[20%_70%] gap-x-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                                    <select wire:model.live="dataModalNew.idGrupoNew" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                                        @foreach($dataModalNew['grupoNew'] as $grupo)
                                            <option value="{{ $grupo->idGrupo }}">{{ $grupo->letra }}</option>
                                        @endforeach


                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tutorado</label>
                                    <select wire:model="dataModalNew.idTutoradoNew" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="{{ null }}">---SELECCIONE TUTORADO---</option>
                                        @foreach($dataModalNew['tutorados'] as $tutorado)
                                            <option value="{{ $tutorado->idCuentaTutorado }}">{{ $tutorado->nombres }} {{ $tutorado->apellidoPaterno }} {{ $tutorado->apellidoMaterno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie del modal -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button wire:click="closeModal()" type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button wire:click="agregarCita" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Agregar Cita
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
