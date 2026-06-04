<div @class([
    'bg-white shadow-lg flex flex-col justify-between py-6 fixed top-0 z-20',
    'transition-all duration-300 ease-in-out',

    // Ancho y alineación general basados en el estado $isOpen
    'w-20' => !$isOpen, // Ancho estrecho si está contraído
    'w-64' => $isOpen, // Ancho amplio si está expandido
    'items-center' => !$isOpen, // Centra los elementos si está contraído
    'items-start px-4' => $isOpen, // Alinea a la izquierda y añade padding si está expandido

    // Altura del sidebar:
    'h-full' => $isOpen, // Ocupa toda la altura si está abierto
    // En pantallas pequeñas y cuando está cerrado, la altura se limita a lo que ocupe el contenido visible.
    // md:h-full asegura que en escritorio, aunque esté "contraído", siga ocupando toda la altura.
    'max-h-[70px] md:h-full' => !$isOpen, // Ajusta esta altura si tu botón es más grande o más pequeño
])>
    {{-- Aseguramos que este div sea un flexbox para controlar la alineación de sus hijos --}}
    <div class="flex items-center w-full mb-8"> {{-- mb-8 para un espacio consistente debajo del encabezado --}}
        {{-- Logo: Oculto si está contraído, visible si está expandido --}}
        <div @class([
            'flex-shrink-0', // Evita que el logo se encoja
            'hidden' => !$isOpen, // Oculto cuando el sidebar está contraído
            'block' => $isOpen, // Visible cuando el sidebar está expandido
        ])>
            {{-- Aquí irá la imagen de tu logo. Asegúrate de que la ruta sea correcta. --}}
            <img src="{{asset('images/tutor/logo-tutor.png')}}" alt="MiTutor Logo" class="h-8">
        </div>

        <button
            class="text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-400 rounded-md p-2 mx-auto"
            wire:click="toggleSidebar" @class([
                // Cuando el sidebar está contraído (!$isOpen), el logo está hidden.
                // Para centrar el hamburguesa, se convierte en el único elemento visible en su flex container.
                // Si solo tiene un hijo, mx-auto centrará ese hijo si no ocupa todo el ancho.
                // En este caso, al ser un flex container y el único visible, flex lo centrará por defecto,
                // pero `mx-auto` asegura el centrado si el flex container no es 'items-center'.
                'mx-auto' => !$isOpen, // Centrado si está contraído (el logo está oculto)
                'ml-auto' => $isOpen, // Alineado a la derecha si está expandido (el logo está visible)
            ])>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-[25px] h-[25px] flex items-center justify-center" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div class="flex flex-col space-y-8 w-full flex-grow overflow-y-auto lg:overflow-y-visible"> {{-- flex-grow para que ocupe el espacio restante --}}
        <nav @class([
            'space-y-6 w-full',
            // Comportamiento en móvil: oculto si no está abierto, visible si sí
            'hidden' => !$isOpen,
            'flex flex-col' => $isOpen,
            // Comportamiento en escritorio: siempre visible, organizado en columna
            'md:flex md:flex-col',
        ])>
            {{-- Inicio --}}
            <a href="{{ route('tutor.index') }}" onclick="@this.setActiveSection('inicio')" @class([
                'flex items-center p-3 rounded-full transition duration-200 ease-in-out',
                'bg-red-400 text-white rounded-full' => request()->routeIs('tutor.index'),
                'text-gray-600 hover:bg-gray-100 hover:text-gray-800'=> !request()->routeIs('tutor.index'),
                    $activeSection !== 'inicio',

                // Alineación de los elementos de navegación
                'justify-center' => !$isOpen, // Centrado si está contraído (solo iconos)
                'justify-start space-x-3' => $isOpen, // Alineado a la izquierda con espacio si está expandido
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Inicio</span>
            </a>

            {{-- Tutorados --}}
            <a href="{{ route('tutor.tutorados.index') }}" @class([
                'flex items-center p-3 rounded-full transition duration-200 ease-in-out',
                'bg-red-400 text-white rounded-full' => request()->routeIs('tutor.tutorados.index','tutor.tutorados.result'),
                    $activeSection === 'tutorados',
                'text-gray-600 hover:bg-gray-100 hover:text-gray-800' => !request()->routeIs('tutor.tutorados.index','tutor.tutorados.result'),
                    $activeSection === 'tutorados',
                'justify-center' => !$isOpen,
                'justify-start space-x-3' => $isOpen,
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Tutorados</span>
            </a>

            {{-- Actividades --}}
            <a href="{{ route('tutor.actividades.index') }}" onclick="@this.setActiveSection('actividades')" @class([
                'flex items-center p-3 rounded-full transition duration-200 ease-in-out',
                'bg-red-400 text-white rounded-full' => request()->routeIs('tutor.actividades.index'),
                    $activeSection === 'actividades',
                'text-gray-600 hover:bg-gray-100 hover:text-gray-800'=> !request()->routeIs('tutor.actividades.index'),
                    $activeSection !== 'actividades',
                'justify-center' => !$isOpen,
                'justify-start space-x-3' => $isOpen,
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Actividades</span>
            </a>

            {{-- Asistencia --}}
            <a href="{{ route('tutor.asistencia.seleccion') }}" onclick="@this.setActiveSection('asistencia')" @class([
                'flex items-center p-3 rounded-full transition duration-200 ease-in-out',
                'bg-red-400 text-white rounded-full' => request()->routeIs('tutor.asistencia.seleccion','tutor-asistencia-index'),
                    $activeSection === 'asistencia',
                'text-gray-600 hover:bg-gray-100 hover:text-gray-800'=> !request()->routeIs('tutor.asistencia.seleccion','tutor-asistencia-index'),
                    $activeSection !== 'asistencia',
                'justify-center' => !$isOpen,
                'justify-start space-x-3' => $isOpen,
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 32"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="2" width="18" height="28" rx="2" ry="2" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l3 3 5-5" />
                </svg>
                <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Asistencia</span>
            </a>

            {{-- Reportes --}}
            <a href="{{ route('tutor.reportes.index') }}" onclick="@this.setActiveSection('reportes')" @class([
                'flex items-center p-3 rounded-full transition duration-200 ease-in-out',
                'bg-red-400 text-white rounded-full' => request()->routeIs('tutor.reportes.index'),
                    $activeSection === 'reportes',
                'text-gray-600 hover:bg-gray-100 hover:text-gray-800'=> !request()->routeIs('tutor.reportes.index'),
                    $activeSection !== 'reportes',
                'justify-center' => !$isOpen,
                'justify-start space-x-3' => $isOpen,
            ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Reportes</span>
            </a>
        </nav>
    </div>

    {{-- Este div se moverá al final gracias a su posicionamiento en el flex container principal --}}
    <div @class([
        'flex flex-col space-y-6 w-full pb-4',
        // Comportamiento en móvil: oculto si no está abierto, visible si sí
        'hidden' => !$isOpen,
        'flex' => $isOpen,
        // Comportamiento en escritorio: siempre visible, organizado en columna
        'md:flex',
    ])>
        {{-- Cerrar Sesión --}}
        <a href="{{ route('login') }}" @class([
            'flex items-center p-3 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition duration-200 ease-in-out',
            'justify-center' => !$isOpen,
            'justify-start space-x-3' => $isOpen,
        ])>
            {{-- Nuevo SVG para Cerrar Sesión --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 flex-shrink-0" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H9m4 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
            </svg>
            <span @class(['hidden' => !$isOpen,'inline' => $isOpen])>Cerrar Sesión</span>
        </a>
    </div>
</div>
