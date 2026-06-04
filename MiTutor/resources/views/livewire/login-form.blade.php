@if (session()->has('error'))
    <div class="bg-red-100 text-red-700 p-2 rounded mb-3 text-sm">
        {{ session('error') }}
    </div>
@endif

<form wire:submit.prevent="login">
    <div class="mb-4">
        <input wire:model="curp" type="text" placeholder="curp"
            class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
        @error('curp') 
            <span class="text-sm text-red-600">{{ $message }}</span> 
        @enderror
    </div>

    <div class="mb-4">
        <input wire:model="contrasena" type="password" placeholder="contraseña"
            class="w-full border border-gray-300 px-3 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300">
        @error('contrasena') 
            <span class="text-sm text-red-600">{{ $message }}</span> 
        @enderror
    </div>

    <div class="flex flex-col space-y-2">
        <button type="submit" class="bg-black text-white py-2 rounded hover:bg-gray-800 transition">
            Iniciar Sesión
        </button>
    </div>
</form>

