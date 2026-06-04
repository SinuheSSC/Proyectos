<div class="bg-gray-100 min-h-screen p-6 font-sans">
    <div class="max-w-6xl mx-auto mb-4">
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md text-sm font-medium">
            Nota: Si requiere modificar su CURP, contacte al administrador del sistema.
        </div>
    </div>

    <form wire:submit.prevent="actualizarInformacion" class="max-w-6xl mx-auto space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                <span class="font-semibold text-lg">Información General</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <label for="nombres" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombres" wire:model.defer="nombres"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="30">
                        @error('nombres')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="apellidoPaterno" class="block text-sm font-medium text-gray-700">Apellido paterno</label>
                        <input type="text" id="apellidoPaterno" wire:model.defer="apellidoPaterno"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="20">
                        @error('apellidoPaterno')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="apellidoMaterno" class="block text-sm font-medium text-gray-700">Apellido materno</label>
                        <input type="text" id="apellidoMaterno" wire:model.defer="apellidoMaterno"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="20">
                        @error('apellidoMaterno')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="fechaNacimiento" class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                        <input type="date" id="fechaNacimiento" wire:model.defer="fechaNacimiento"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('fechaNacimiento')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                        <select id="genero" wire:model.defer="genero" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Selecciona</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                        @error('genero')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-center items-start mt-6">
                        <div class="text-center">
                            @if ($nuevaFoto)
                                <img src="{{ $nuevaFoto->temporaryUrl() }}"
                                    class="w-32 h-32 rounded-full object-cover mx-auto mb-2">
                            @else
                                <img src="{{ $foto }}"
                                    class="w-32 h-32 rounded-full object-cover mx-auto mb-2"
                                    alt="Foto de perfil">
                            @endif

                            <input type="file" wire:model="nuevaFoto" accept="image/*" class="mt-2 text-sm">
                            @error('nuevaFoto')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-[#FFEEEC] text-[#F5A89F] p-2 rounded-full w-max mx-auto sm:mx-0 mb-4 sm:mb-2">
                <span class="font-semibold text-lg">Editar Información Adicional</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" id="telefono" wire:model.defer="telefono"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="11" pattern="[0-9]{10,11}" title="Ingresa un número de teléfono válido (10 u 11 dígitos)">
                        @error('telefono')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="estadoCivil" class="block text-sm font-medium text-gray-700">Estado civil</label>
                        <select id="estadoCivil" wire:model.defer="estadoCivil" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Selecciona</option>
                            <option value="Soltero/a">Soltero/a</option>
                            <option value="Casado/a">Casado/a</option>
                            <option value="Viudo/a">Viudo/a</option>
                        </select>
                        @error('estadoCivil')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" wire:model.defer="email"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="50">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="municipio" class="block text-sm font-medium text-gray-700">Municipio</label>
                        <input type="text" id="municipio" wire:model.defer="municipio"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="30">
                        @error('municipio')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select id="estado" wire:model.defer="estado" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Selecciona</option>
                            <option>Michoacán</option>
                            <option>Otro</option>
                        </select>
                        @error('estado')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="calleYnumero" class="block text-sm font-medium text-gray-700">Calle y número</label>
                        <input type="text" id="calleYnumero" wire:model.defer="calleYnumero"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="70">
                        @error('calleYnumero')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="codigoPostal" class="block text-sm font-medium text-gray-700">Código postal</label>
                        <input type="text" id="codigoPostal" wire:model.defer="codigoPostal"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required maxlength="5" pattern="[0-9]{5}" title="Ingresa un código postal de 5 dígitos">
                        @error('codigoPostal')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="colonia" class="block text-sm font-medium text-gray-700">Colonia</label>
                        <input type="text" id="colonia" wire:model.defer="colonia"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            maxlength="50">
                        @error('colonia')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @if (session()->has('mensaje'))
            <div class="max-w-6xl mx-auto mb-6">
                <div
                    class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded-md text-sm font-medium">
                    {{ session('mensaje') }}
                </div>
            </div>
        @endif
        <div class="max-w-6xl mx-auto flex justify-end gap-3 pt-4">
            <button type="button" onclick="window.location='{{ route('tutor.index') }}'" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                Regresar
            </button>

            <div class="text-right">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full sm:w-auto">
                    Guardar
                </button>
            </div>
        </div>
    </form>
</div>
