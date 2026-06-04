<!-- Componente de inicio para estudiantes -->
<!-- Ajustado para integrarse con el template-estudiante.blade.php -->
<div class="py-6">
    <!-- Banner de bienvenida con estilo similar a la imagen de referencia -->


    <!-- Sección principal con opciones -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel izquierdo (ocupa 2/3 en desktop) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos generales -->
            <div class="bg-white rounded-lg shadow">
                @if ($tutorado)
    <div class="bg-[#86A0FE] text-white p-1 rounded-t-lg shadow-sm mb-2 text-center">
        <h2 class="text-xl font-medium">Bienvenido {{ $tutorado->nombres }}</h2>
    </div>
@else
    <div class="bg-[#86A0FE] text-white p-1 rounded-t-lg shadow-sm mb-2 text-center">
        <h2 class="text-xl font-medium">Bienvenido, Usuario desconocido</h2>
    </div>
@endif

                <div class="p-4 pl-0">

                   <h3 class="text-black font-normal mb-4 pl-3 flex items-center gap-2">Seleccione la opción que requiera de acuerdo a sus actividades en:
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                   </h3>

                   <h3 class="bg-[#D9D9D9] rounded-r-lg px-4 py-0 text-black font-normal inline-block">Datos generales:</h3>



                    @if ($tutorado)
    <div class="rounded-md p-1">
        <div class="space-y-2">
            <p class="pl-3"><span class="font-medium">Matrícula: {{ $tutorado->idCuentaTutorado }} </span> </p>
            <p class="pl-3"><span class="font-medium">Carrera deseada: {{ $tutorado->carrera }} </span> </p>
            <p class="pl-3"><span class="font-medium">Razones carrera: {{ $tutorado->razonesCarrera }} </span> </p>

            <p class="pl-3">
                <span class="font-medium">Necesidad especial:</span>
                <!-- Cambio aquí: usar la variable del componente en lugar del texto estático -->
                <span class="@if($necesidad_especial === 'SI') bg-red-100 text-red-600 @else bg-red-100 text-red-600 @endif px-2 py-0.5 rounded text-xs font-semibold">
                    {{ $necesidad_especial }}
                </span>
            </p>
        </div>
    </div>
@else
    <div class="rounded-md p-1">
        <p class="text-red-500 font-semibold">No se encontró información del tutorado.</p>
    </div>
@endif

                </div>
            </div>

            <!-- Guía rápida del sistema -->
            <div class="bg-white rounded-r-lg shadow overflow-hidden border-l-4 border-[#86A0FE]">
                <div class="p-4">
                    <h3 class="text-[#86A0FE] bg-[#EEF2FF] font-semibold mb-6 inline-block px-4 py-1 rounded-full">
                        Guía rápida del sistema
                    </h3>

                    <div class="space-y-5">
                        <!-- Inicio -->
                        <div class="flex items-start gap-4">
                            <div class="text-gray-700 shrink-0">
                                <!-- Icono inicio -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Inicio:</p>
                                <p class="text-gray-600">Estás aquí.</p>
                            </div>
                        </div>

                        <!-- Mi información -->
                        <div class="flex items-start gap-4">
                            <div class="text-gray-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Mi información:</p>
                                <p class="text-gray-600">Aquí puedes consultar tu información general, editar tu información y si tienes alguna necesidad especial, puedes indicarlo.</p>
                            </div>
                        </div>

                        <!-- Examen de vocación -->
                        <div class="flex items-start gap-4">
                            <div class="text-gray-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Examen de vocación:</p>
                                <p class="text-gray-600">Aquí puedes realizar el examen de vocación y consultar los resultados del examen.</p>
                            </div>
                        </div>

                        <!-- Libreta de actividades -->
                        <div class="flex items-start gap-4">
                            <div class="text-gray-700 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">Libreta de actividades:</p>
                                <p class="text-gray-600">Aquí puedes realizar diferentes test y consultar resultados.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Panel derecho (ocupa 1/3 en desktop) -->
        <div class="space-y-6">
            <!-- Tarjeta de información de MiTutor -->
            <div class="bg-white rounded-r-lg shadow overflow-hidden border-l-4 border-[#86A0FE]">
                <div class="p-6">
                    <h3 class="text-[#86A0FE] bg-[#EEF2FF] font-semibold mb-6 inline-block px-4 py-1 rounded-full">
                        Información de MiTutor
                    </h3>

                    <div class="space-y-4 text-gray-800 leading-relaxed">
                        <p>
                            En MiTutor podrás encontrar diferentes test para tu formación, entre los principales tenemos el examen de vocación, análisis FODA, línea de vida, examen de comprensión lectora entre otros.
                        </p>

                        <p>
                            Tu tutor estará al pendiente de tus resultados en estos test.
                        </p>

                        <p>
                            Si requieres atención especial lo puedes pedir en el módulo de “Mi información” y llenar el formulario (tu tutor te dará seguimiento).
                        </p>

                        <p>
                            Puedes consultar los resultados de los test en el cuaderno de actividades.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
