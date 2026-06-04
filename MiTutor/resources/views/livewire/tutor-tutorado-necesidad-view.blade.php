<div class="mx-auto px-4 py-8 w-full flex items-center justify-center">
    <div class="w-full flex items-center justify-center">
        {{-- Contenedor principal de las tarjetas, usando grid para layout responsivo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-[80%]">
            {{-- Primera columna (o fila completa en móvil) --}}
            <div class="md:col-span-1">
                <div class="bg-white p-4 rounded-lg shadow-md mb-4 flex flex-col md:flex-row items-center gap-4">
                    <div
                        class="flex-shrink-0 w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-gray-200">
                        {{-- Aquí iría la imagen del alumno --}}
                        <img src="{{ $alumno->foto_perfil }}" alt="Foto de {{ $alumno->nombre }}"
                            class="w-full h-full object-cover">
                    </div>

                    <div class="flex-grow text-center md:text-left">
                        <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                            <span class="font-semibold text-lg">Información General</span>
                        </div>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><strong>Nombre:</strong> {{ $alumno->nombre }}</p>
                            <p><strong>Apellido paterno:</strong> {{ $alumno->apellido_paterno }}</p>
                            <p><strong>Apellido materno:</strong> {{ $alumno->apellido_materno }}</p>
                            <p><strong>Fecha de nacimiento:</strong>
                                {{ \Carbon\Carbon::parse($alumno->fecha_nacimiento)->format('d/m/Y') }}</p>
                            <p><strong>Género:</strong> {{ $alumno->genero }}</p>
                            <p><strong>CURP:</strong> {{ $alumno->curp }}</p>
                            @if (isset($alumno->tutor_asignado))
                                {{-- Si tienes un campo para el tutor asignado --}}
                                <p><strong>Tutor asignado:</strong> {{ $alumno->tutor_asignado }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Segunda columna (o debajo en móvil) --}}
            <div class="md:col-span-1">
                <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                    <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                        <span class="font-semibold text-lg">Necesidad especial</span>
                    </div>

                    <div class="text-sm text-gray-700 space-y-1 mt-2">
                        <div class="text-sm text-gray-700 space-y-2 mt-2">
                            <div>
                                <p><strong>Discapacidad:</strong></p>
                                <div class="flex flex-nowrap overflow-x-auto py-1 space-x-2">
                                    @if ($alumno->discapacidad !== 'NO')
                                        <span
                                            class="flex-shrink-0 bg-yellow-200 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $alumno->discapacidad }}
                                        </span>
                                    @else
                                        <span
                                            class="flex-shrink-0 bg-yellow-100 text-yellow-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            No aplica
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p><strong>Enfermedad:</strong></p>
                                <div class="flex flex-nowrap overflow-x-auto py-1 space-x-2">
                                    @if ($alumno->enfermedad !== 'NO')
                                        <span
                                            class="flex-shrink-0 bg-red-300 text-stone-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $alumno->enfermedad }}
                                        </span>
                                    @else
                                        <span
                                            class="flex-shrink-0 bg-stone-200 text-stone-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            No aplica
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p><strong>Condición especial:</strong></p>
                                <div class="flex flex-nowrap overflow-x-auto py-1 space-x-2">
                                    @if (!empty($alumno->condicion_especial) && $alumno->condicion_especial !== 'NO')
                                        <span
                                            class="flex-shrink-0 bg-blue-200 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $alumno->condicion_especial }}
                                        </span>
                                    @else
                                        <span
                                            class="flex-shrink-0 bg-blue-100 text-blue-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            No aplica
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- La tarjeta de actividades siempre ocupará todo el ancho en móviles y md+.
                En la imagen original, está debajo de ambas columnas. --}}
            <div class="col-span-full">
                <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                    <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                        <span class="font-semibold text-lg text-[#F5A89F]">Estado de atención</span>
                    </div>
                    @forelse ($citas as $cita)
                        <div class="flex items-center p-3 border border-gray-200 rounded-md">
                            <div class="flex flex-wrap items-center gap-2 flex-grow">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $cita->canalizacion ?? 'Tipo no especificado' }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $cita->resultados ?? 'Sin resultados' }}
                                </span>
                                <span class="text-gray-800 text-sm font-medium ml-2">
                                    {{ \Carbon\Carbon::parse($cita->fechaCita)->format('d \d\e F \a \l\a\s h:i A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No hay citas registradas para este alumno.</p>
                    @endforelse

                    <div class="flex justify-end mt-4">
                        <button wire:click="openModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md shadow">
                            Agregar necesidad
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Livewire Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">
                <div class="flex justify-between items-center mb-4">
                    <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max">
                        <span class="font-semibold text-lg">Agregar atención</span>
                    </div>
                    <button wire:click="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                        &times;
                    </button>
                </div>

                <form wire:submit.prevent="saveAttention">
                    <div class="space-y-4">
                        <div>
                            <label for="fechaCita" class="block text-sm font-medium text-gray-700 mb-1">Fecha de la
                                cita:</label>
                            <input type="datetime-local" id="fechaCita" wire:model="fechaCita"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                            @error('fechaCita')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="canalizacion"
                                class="block text-sm font-medium text-gray-700 mb-1">Canalización</label>
                            <select id="canalizacion" wire:model="canalizacion"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value=" disabled selected">Selecciona una opción</option>
                                <option value="Psicologia">Psicologia</option>
                                <option value="Enfermeria">Enfermeria</option>
                            </select>
                            @error('canalizacion')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Agregar
                                Descripción</label>
                            <textarea id="descripcion" rows="4" placeholder="Agregar Descripción" wire:model="descripcion"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm resize-y"
                                required maxlength="500"></textarea>
                            @error('descripcion')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="resultados" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select id="resultados" wire:model="resultados"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                                <option value="disabled selected">Selecciona una opción</option>
                                <option value="En atención">En atención</option>
                                <option value="En revisión">En revisión</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Concluida">Concluida</option>
                            </select>
                            @error('resultados')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600
                    transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500
                    focus:ring-opacity-75">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
