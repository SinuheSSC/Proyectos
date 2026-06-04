<div class="min-h-screen p-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Left Side - Special Needs Form -->
        <div class="bg-white rounded-lg shadow-md p-6" x-data="{
            disability: '', illness: '', condition: ''
        }">
            <h2 class="text-lg font-medium text-blue-400 bg-blue-50 py-2 px-4 rounded-lg mb-6">Necesidad especial</h2>

            <!-- Pregunta 1: Discapacidad -->
<div class="mb-4">
    <label class="block text-gray-700 mb-2">¿Cuenta con alguna discapacidad?</label>
    <select wire:model.defer="hasDisability" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" x-model="disability">
        <option value="">--Seleccione una opción--</option>
        <option value="Sí">Sí</option>
        <option value="No">No</option>
    </select>
</div>
<div class="mb-4" x-show="disability === 'Sí'" x-transition>
    <label class="block text-gray-700 mb-2">¿Cuál?</label>
    <textarea wire:model.defer="disabilityDetails" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Explique aquí..."></textarea>
</div>

<!-- Pregunta 2: Enfermedad -->
<div class="mb-4">
    <label class="block text-gray-700 mb-2">¿Usted tiene alguna enfermedad?</label>
    <select wire:model.defer="hasIllness" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" x-model="illness">
        <option value="">--Seleccione una opción--</option>
        <option value="Sí">Sí</option>
        <option value="No">No</option>
    </select>
</div>
<div class="mb-4" x-show="illness === 'Sí'" x-transition>
    <label class="block text-gray-700 mb-2">¿Cuál?</label>
    <textarea wire:model.defer="illnessDetails" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Explique aquí..."></textarea>
</div>

<!-- Pregunta 3: Condición especial -->
<div class="mb-4">
    <label class="block text-gray-700 mb-2">¿Usted tiene alguna situación psicologica?</label>
    <select wire:model.defer="PsychologicalSituation" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" x-model="condition">
        <option value="">--Seleccione una opción--</option>
        <option value="Sí">Sí</option>
        <option value="No">No</option>
    </select>
</div>
<div class="mb-4" x-show="condition === 'Sí'" x-transition>
    <label class="block text-gray-700 mb-2">¿Cuál?</label>
    <textarea wire:model.defer="PsychologicalSituationDetails" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Explique aquí..."></textarea>
</div>

<!-- Botón Guardar -->
<div class="text-right">
    <button wire:click="saveSpecialNeeds" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
        Guardar
    </button>
</div>

<!-- Mensaje de éxito -->
@if (session()->has('message'))
    <div class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded-md text-sm font-medium">
        {{ session('message') }}
    </div>
@endif

        </div>

        <!-- Right Side - General Information -->
        <div class="space-y-4">
            <!-- General Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6 relative">
                <div class="absolute top-3 right-3 bg-blue-500 text-white py-1 px-3 rounded-full text-sm font-semibold">
                        <div><span class="font-semibold">MATRICULA:</span> {{ $tutorado->idCuentaTutorado }}</div>
                </div>

                <h2 class="text-lg font-medium text-blue-400 bg-blue-50 py-2 px-4 rounded-lg mb-6 mt-7">Mi información general</h2>

                <div class="flex flex-wrap md:flex-nowrap">
                    <div class="w-full md:w-2/3 space-y-2 mb-4 md:mb-0">
                    @if($tutorado)
                        <div class="space-y-2">
                            <p><strong>Nombre:</strong> {{ $tutorado->nombres }}</p>
                            <p><strong>CURP:</strong> {{ $tutorado->curp }}</p>
                            <p><strong>Correo:</strong> {{ $tutorado->email }}</p>
                            <p><strong>Teléfono:</strong> {{ $tutorado->telefono }}</p>
                            <p><strong>Carrera:</strong> {{ $tutorado->carrera }}</p>
                            <!-- Agrega los campos que tengas en tu tabla -->
                        </div>
                    @else
                        <div class="text-red-600 font-semibold">
                            No se encontró información del tutorado con CURP: {{ $curp }}
                        </div>
                    @endif
                    </div>

                    <div class="w-full md:w-1/3 flex justify-center items-start">
                        <div class="w-32 h-32 overflow-hidden rounded-full">
                            <img src="{{ asset('storage/' . $tutorado->fotoPerfil) }}" alt="Foto de perfil">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('mi-informacionEe') }}">
                        <button class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-1 px-3 rounded text-sm">
                            Editar datos
                        </button>
                    </a>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-blue-400 bg-blue-50 py-2 px-4 rounded-lg mb-6">Información adicional</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div><span class="font-semibold">Teléfono:</span> {{ $tutorado->telefono }}</div>
                        <div><span class="font-semibold">Estado civil:</span> {{ $tutorado->estadoCivil }}</div>
                        <div><span class="font-semibold">Email:</span> {{ $tutorado->email }}</div>
                        <div><span class="font-semibold">Municipio:</span> {{ $tutorado->municipio }}</div>
                    </div>
                    <div class="space-y-2">
                        <div><span class="font-semibold">Estado:</span> {{ $tutorado->estado }}</div>
                        <div><span class="font-semibold">Calle y número:</span> {{ $tutorado->calleYnumero }}</div>
                        <div><span class="font-semibold">Código postal:</span> {{ $tutorado->codigoPostal }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
