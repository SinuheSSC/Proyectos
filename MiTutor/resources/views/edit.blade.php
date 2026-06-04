<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example Crud API</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">
    <main>
        <div class="flex flex-col mt-10 justify-center items-center w-full h-screen">
        
            <div class="bg-white px-6 py-4">
                <form method="POST" action="{{ route('users.update',$user->curp) }}">
                @csrf
                @method('PUT')
                    <x-label>Curp</x-label>
                    <x-input value="{{ $user->curp }}" disabled></x-input>

                    <x-label>Contraseña</x-label>
                    <x-input type="password" id="contrasena" name="contrasena"></x-input>

                    <x-label>Rol</x-label>
                    <select id="rol" name="rol" class="block mt-1 w-full">
                        <option value="A">Administrador</option>
                        <option value="P">Tutor</option>
                        <option value="E">Tutorado</option>
                    </select>

                    <x-button class="mt-4">
                        Editar
                    </x-button>
                </form>
            </div>  
            
            
        
        
        </div>
    </main>
</body>
</html>
