<div class="bg-gray-100 min-h-screen p-6">
    <!-- Nota superior -->
    <div class="max-w-6xl mx-auto mb-4">
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md text-sm font-medium">
            Nota: Si requiere modificar su CURP, contacte al administrador del sistema.
        </div>
    </div>

    <form wire:submit.prevent="actualizarInformacion" class="max-w-6xl mx-auto space-y-6">
    <!-- Tarjeta: Información general -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-blue-500 bg-blue-50 px-4 py-2 rounded-lg mb-6 inline-block">Editar información general</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" wire:model.defer="nombres" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('nombres') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" wire:model.defer="apellidoPaterno" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('apellidoPaterno') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" wire:model.defer="apellidoMaterno" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('apellidoMaterno') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" wire:model.defer="fechaNacimiento" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('fechaNacimiento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Género</label>
                        <select wire:model.defer="genero" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                            <option value="">Selecciona</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>

                        </select>
                        @error('genero') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-center items-start mt-6">
    <div class="text-center">
        {{-- Foto actual --}}
        @if ($foto)
            <img src="{{ asset('storage/' . $foto) }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-2">
        @else
            <img src="{{ asset('images/avatar.png') }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-2">
        @endif

        {{-- Subir nueva foto --}}
        <input type="file" wire:model="nuevaFoto" accept="image/*" class="mt-2 text-sm">
        @error('nuevaFoto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        {{-- Previsualización de la nueva foto --}}
        @if ($nuevaFoto)
            <div class="mt-2">
                <span class="text-sm text-gray-600">Previsualización:</span>
                <img src="{{ $nuevaFoto->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover mx-auto">
            </div>
        @endif
    </div>
</div>

            </div>
        </div>

        <!-- Tarjeta: Información adicional -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-blue-500 bg-blue-50 px-4 py-2 rounded-lg mb-6 inline-block">Editar información adicional</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Columna izquierda -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" wire:model.defer="telefono" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado civil</label>
                        <select wire:model.defer="estadoCivil" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                            <option value="">Selecciona</option>
                            <option value="Soltero/a">Soltero/a</option>
                            <option value="Casado/a">Casado</option>
                            <option value="Viudo/a">Viudo/a</option>
                        </select>
                        @error('estadoCivil') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model.defer="email" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Municipio</label>
                        <input type="text" wire:model.defer="municipio" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('municipio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <input wire:model.defer="estado" type="text" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">

                        @error('estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Calle y número</label>
                        <input type="text" wire:model.defer="calleYnumero" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('calleYnumero') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código postal</label>
                        <input type="text" wire:model.defer="codigoPostal" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('codigoPostal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Colonia</label>
                        <input type="text" wire:model.defer="colonia" class="w-full mt-1 rounded-md border-gray-300 shadow-sm">
                        @error('colonia') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>
        @if (session()->has('mensaje'))
        <div class="max-w-6xl mx-auto mb-6">
            <div class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded-md text-sm font-medium">
                {{ session('mensaje') }}
            </div>
        </div>
    @endif
        <!-- Botones de acción -->
        <div class="max-w-6xl mx-auto flex justify-end gap-3 pt-4">
        <button type="button" onclick="window.location='{{ route('mi-informacionE') }}'" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
            Regresar
        </button>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm">
                Guardar
            </button>
        </div>
    </form>

</div>
