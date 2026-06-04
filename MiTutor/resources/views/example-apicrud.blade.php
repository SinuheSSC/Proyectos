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
         
            
            <table class="w-3/4 border-collapse border border-gray-300 mt-4">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2 w-1/3">CURP</th>
                        <th class="border p-2 w-1/3">ROL</th>
                        <th class="border p-2 w-1/3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="py-4">{{ $user->curp }}</td>
                        <td>{{ $user->rol }}</td>
                        <td class="flex justify-center items-center space-x-2">
                            <a href="{{ route('users.show', $user->curp) }}">
                                <x-button>Editar</x-button>
                            </a>    
            
                            <form action="{{ route('users.destroy', $user->curp) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Borrar</x-danger-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
