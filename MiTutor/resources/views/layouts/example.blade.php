<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mi Tutor</title>
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Incluye CSS y JS con Vite --}}
        @livewireStyles
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="bg-[#F4EFF1]">


            <main>
                @yield('content')
            </main>


        @livewireScripts
    </body>
</html>
