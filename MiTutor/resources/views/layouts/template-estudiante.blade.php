<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MiTutor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex" x-data="{
            sidebarOpen: window.innerWidth >= 1024,
            userDropdownOpen: false,
            init() {
                this.handleResize();
                window.addEventListener('resize', () => this.handleResize());
            },
            handleResize() {
                if (window.innerWidth >= 1024) {
                    this.sidebarOpen = true;
                } else {
                    this.sidebarOpen = false;
                }
            }
        }">
            <!-- Overlay para móvil -->
            <div
                x-show="sidebarOpen && window.innerWidth < 1024"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden">
            </div>

            <!-- Botón toggle móvil (fuera del sidebar) -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                x-show="!sidebarOpen"
                class="fixed top-4 left-4 z-40 lg:hidden bg-white p-2 rounded-md shadow-lg border">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Sidebar -->
            <div class="h-screen bg-white shadow transition-all duration-300 fixed z-30"
                 :class="{
                    'w-64 translate-x-0': sidebarOpen,
                    'w-64 -translate-x-full lg:translate-x-0 lg:w-16': !sidebarOpen
                 }">

                <!-- Header del sidebar -->
                <div class="relative">
                    <div class="flex items-center justify-between p-4">
                        <!-- Logo -->
                        <div x-show="sidebarOpen" x-transition class="flex-1 flex justify-center">
                            <img src="{{ asset('images/logoEstudiante.png') }}" alt="Logo MiTutor" class="h-7 w-auto object-contain" />
                        </div>

                        <!-- Botón toggle desktop -->
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:block p-1 rounded-md hover:bg-gray-100 transition-colors"
                            :class="sidebarOpen ? 'ml-2' : 'mx-auto'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800 transition-transform duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 :class="sidebarOpen ? '' : 'rotate-180'">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Botón cerrar móvil (X cuando está abierto) -->
                        <button
                            @click="sidebarOpen = false"
                            x-show="sidebarOpen"
                            class="lg:hidden p-1 rounded-md hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Línea separadora -->
                    <div x-show="sidebarOpen" x-transition class="border-t border-gray-200 mx-4"></div>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-6 px-4 space-y-3">
                    <!-- Inicio -->
                    <a href="{{ route('dashboardE') }}"
                       class="flex items-center gap-3 px-4 py-2 font-medium text-sm transition-all duration-200 group relative
                              {{ request()->routeIs('dashboardE') ? 'bg-[#86A0FE] text-white' : 'text-black hover:bg-[#dcdcdc]' }}"
                       :class="sidebarOpen ? 'rounded-full' : 'justify-center rounded-full w-10 h-10 p-0 mx-auto'">
                        <div class="p-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-transition>Inicio</span>
                        <!-- Tooltip para sidebar colapsado -->
                        <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Inicio
                        </div>
                    </a>

                    <!-- Mi Información -->
                    <a href="{{ route('mi-informacionE') }}"
                       class="flex items-center gap-3 px-4 py-2 font-medium text-sm transition-all duration-200 group relative
                              {{ request()->routeIs('mi-informacionE', 'mi-informacionEe') ? 'bg-[#86A0FE] text-white' : 'text-black hover:bg-[#dcdcdc]' }}"
                       :class="sidebarOpen ? 'rounded-full' : 'justify-center rounded-full w-10 h-10 p-0 mx-auto'">
                        <div class="p-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-transition>Mi Información</span>
                        <!-- Tooltip para sidebar colapsado -->
                        <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Mi Información
                        </div>
                    </a>

                    <!-- Examen Vocación -->
                    <a href="{{ route('examenv') }}"
                       class="flex items-center gap-3 px-4 py-2 font-medium text-sm transition-all duration-200 group relative
                              {{ request()->routeIs('examenv', 'examenv.preguntas', 'examenv.resultados*') ? 'bg-[#86A0FE] text-white' : 'text-black hover:bg-[#dcdcdc]' }}"
                       :class="sidebarOpen ? 'rounded-full' : 'justify-center rounded-full w-10 h-10 p-0 mx-auto'">
                        <div class="p-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-transition>Examen Vocación</span>
                        <!-- Tooltip para sidebar colapsado -->
                        <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Examen Vocación
                        </div>
                    </a>

                    <!-- Cuaderno Actividades -->
                    <a href="{{ route('cuaderno.actividades')}}"
                       class="flex items-center gap-3 px-4 py-2 font-medium text-sm transition-all duration-200 group relative
                              {{ request()->routeIs('cuaderno.actividades', 'lineavida', 'analisisfodae', 'comprensionlectorae') ? 'bg-[#86A0FE] text-white' : 'text-black hover:bg-[#dcdcdc]' }}"
                       :class="sidebarOpen ? 'rounded-full' : 'justify-center rounded-full w-10 h-10 p-0 mx-auto'">
                        <div class="p-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-transition>Cuaderno Actividades</span>
                        <!-- Tooltip para sidebar colapsado -->
                        <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Cuaderno Actividades
                        </div>
                    </a>

                    <!-- Citas -->
                    <a href="{{ route('citas.tutorados')}}"
                       class="flex items-center gap-3 px-4 py-2 font-medium text-sm transition-all duration-200 group relative
                              {{ request()->routeIs('citas.tutorados') ? 'bg-[#86A0FE] text-white' : 'text-black hover:bg-[#dcdcdc]' }}"
                       :class="sidebarOpen ? 'rounded-full' : 'justify-center rounded-full w-10 h-10 p-0 mx-auto'">
                        <div class="p-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" x-transition>Citas</span>
                        <!-- Tooltip para sidebar colapsado -->
                        <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Citas
                        </div>
                    </a>
                </nav>

                <!-- Logout Button -->
                <div class="absolute bottom-4 w-full px-3">
                    <div x-show="sidebarOpen" x-transition class="border-t border-gray-200 mx-4 mb-3"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full transition-colors duration-200 text-gray-700 hover:bg-gray-100 group relative"
                                :class="sidebarOpen ? 'p-3 rounded-md gap-3' : 'justify-center p-2 rounded-full w-10 h-10 mx-auto'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition>Cerrar Sesión</span>
                            <!-- Tooltip para sidebar colapsado -->
                            <div x-show="!sidebarOpen" class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                Cerrar Sesión
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contenedor principal (Header y contenido) -->
            <div class="flex-1 flex flex-col transition-all duration-300"
                 :class="{'lg:ml-64': sidebarOpen, 'lg:ml-16': !sidebarOpen}">

                <!-- Header: ocupa ancho completo, pero su contenido se centra -->
                <header class="w-full p-4 bg-transparent">
                    <div class="max-w-6xl mx-auto flex items-center justify-between">
                        <h1 class="text-xl font-semibold text-gray-800"></h1>

                        <!-- Dropdown del usuario -->
                        <div class="relative">
                            <!-- Botón de perfil -->
                            <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center space-x-2 bg-[#86A0FE] text-white px-4 py-2 rounded-full shadow-md">
                                @livewire('foto-perfil')
                                @livewire('nombre_completo')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Menú desplegable -->
                            <div
                                x-show="userDropdownOpen"
                                @click.outside="userDropdownOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50"
                            >
                                <div class="py-1 border border-gray-100 rounded-md">
                                    <!-- Opción Mi información -->
                                    <a href="{{ route('mi-informacionE') }}" class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Mi Información
                                    </a>

                                    <!-- Opción Cerrar Sesión -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Contenido de la página -->
                <main class="flex-1 px-4 md:px-6">
                    <div class="max-w-6xl mx-auto w-full">
                        @yield('content')
                        {{ $slot ?? '' }}
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
