<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Tutor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 overflow-auto" x-data="{ expanded: false }"
    x-init="expanded = JSON.parse(localStorage.getItem('sidebar_expanded')) ?? false"
    @click.outside="localStorage.setItem('sidebar_expanded', JSON.stringify(expanded))">

    <div class="flex h-screen w-full">
        {{-- Sidebar --}}
        <div class="h-full fixed left-0 transition-all duration-300 flex flex-col bg-white shadow-md border-r justify-between items-center z-50"
            :class="expanded ? 'w-48' : 'w-16'">

            {{-- Toggle --}}
            <div class="w-full flex justify-center mt-3">
                <button
                    @click="expanded = !expanded; localStorage.setItem('sidebar_expanded', JSON.stringify(expanded))"
                    class="text-xl flex items-center gap-2 py-3 focus:outline-none transition-all duration-300"
                    :class="expanded ? 'px-2' : 'justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 transform transition-transform duration-700"
                        :class="expanded ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <img src="{{ asset('images/AdmLogo.png') }}" alt="Logo" class="h-10 ml-1" x-show="expanded">
                </button>
            </div>

            {{-- Navegación --}}
            <nav class="flex flex-col items-center gap-3 w-full flex-1 mt-4">
                <a href="{{ route('admin-home-page') }}" class="w-full flex justify-center">
                    <button class="flex items-center gap-3 px-4 py-2 text-lg rounded-md transition hover:bg-gray-100">
                        <svg class="h-7 w-7 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 9.75L12 3l9 6.75V20.25A1.5 1.5 0 0119.5 21H4.5A1.5 1.5 0 013 20.25V9.75z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V12h6v9" />
                        </svg>
                        <span x-show="expanded" class="text-xl mr-16">Inicio</span>
                    </button>
                </a>

                <a href="{{ route('lista-tutores-tutorados') }}" class="w-full flex justify-center">
                    <button class="flex items-center gap-3 px-4 py-2 text-lg rounded-md transition hover:bg-gray-100">
                        <svg class="h-7 w-7 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        <span x-show="expanded" class="text-xl mr-16">Lista</span>
                    </button>
                </a>

                <a href="{{ route('admin-agregar-user') }}" class="w-full flex justify-center">
                    <button class="flex items-center gap-3 px-4 py-2 text-lg rounded-md transition hover:bg-gray-100">
                        <svg class="h-8 w-8 text-gray-700 group-hover:text-blue-600 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <!-- Icono de usuario original -->
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                <!-- Pequeño "+" en esquina superior derecha -->
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3"
                                    class="text-green-500"
                                    transform="translate(4, -4) scale(0.6)" />
                        </svg>
                        <span x-show="expanded" class="text-xl mr-8">Registrar</span>
                    </button>
                </a>

                <a href="{{ route('admin-citas') }}" class="w-full flex justify-center">
                    <button class="flex items-center gap-3 px-4 py-2 text-lg rounded-md transition hover:bg-gray-100">
                        <svg class="h-8 w-8 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 2.25V4.5M18 2.25V4.5M3.75 8.25h16.5M4.5 6.75h15a1.5 1.5 0 011.5 1.5v11.25A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 19.5V8.25a1.5 1.5 0 011.5-1.5z" />
                        </svg>
                        <span x-show="expanded" class="text-xl mr-16">Citas</span>
                    </button>
                </a>
            </nav>

            {{-- Logout --}}
            <div class="mb-4 w-full flex justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center gap-3 px-4 py-2 text-lg rounded-md transition hover:bg-gray-100">
                        <svg class="h-8 w-8 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18.75 12h-9" />
                        </svg>
                        <span x-show="expanded" class="text-xl mr-16">Salir</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="flex-1 flex flex-col h-full transition-all duration-300 relative z-40" :class="expanded ? 'ml-48' : 'ml-16'">

            {{-- Topbar --}}
            <nav class="sticky top-0 p-4 flex justify-between items-center bg-white shadow-md z-30 h-16">
                <div x-show="!expanded" x-transition class="hidden md:flex items-center">
                    <img src="{{ asset('images/AdmLogo.png') }}" alt="Logo" class="h-10">
                </div>

                <div
                    class="flex items-center gap-2 bg-green-100 px-4 py-1.5 rounded-full hover:bg-green-200 transition cursor-pointer">
                    <div class="w-7 h-7 bg-green-300 rounded-full overflow-hidden">
                        <img src="{{ asset('images/Chad.jpg') }}"
                            class="object-cover w-full h-full" alt="Perfil">
                    </div>
                    <span class="text-lg font-semibold text-gray-800">
                        {{ session('nameAdmin') ?? 'Usuario' }}
                    </span>
                </div>
            </nav>

            {{-- Contenido dinámico --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>
