<div>
    <div class="grid grid-cols-[60%_auto] gap-x-10 mx-auto px-4 py-8 w-[90%]">

        <!-- Sección de datos generales -->
        <div>
            <div class=" h-fit  bg-white rounded-lg shadow-md max-w-md mx-auto w-full mb-8 sm:max-w-lg md:max-w-xl lg:max-w-2xl">
                <div class="bg-green-400 text-white p-2 rounded-t-lg text-center text-md">
                    <h1 class="font-bold">Bienvenido/a {{ $user->nombres }} {{ $user->apellidoPaterno }} {{ $user->apellidoMaterno }}</h1>
                </div>
                <div class="flex mb-2 p-2 items-center justify-center gap-1">
                    <p class="text-sm sm:text-base">Seleccione la opción que requiera de acuerdo a sus actividades en:</p>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[25px] h-[25px] text-gray-800" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
                <div class="bg-[#D9D9D9] px-2 py-1 rounded-e mb-2 w-fit h-fit">
                    <h2 class=" text-sm w-fit">Datos generales:</h2>
                </div>
                <div class="p-4 grid grid-cols-2">
                    <p class="text-xs sm:text-base">CURP: {{ $user->curp }}</p>
                    <p class="text-xs sm:text-base">RFC: {{ $user->RFC }}</p>
                    <p class="text-xs sm:text-base">Fecha Nacimiento: {{ $user->fechaNacimiento }}</p>
                    <p class="text-xs sm:text-base">Genero: {{ $user->genero }}</p>
                    <p class="text-xs sm:text-base">Telefono: {{ $user->telefono }}</p>
                    <p class="text-xs sm:text-base">Email: {{ $user->email }}</p>

                </div>

            </div>

            <div class="bg-white rounded-lg shadow-md flex flex-col max-w-md mx-auto w-full sm:flex-col sm:max-w-lg md:max-w-xl lg:max-w-2xl">
                <div class="w-full h-[5px] sm:h-auto sm:w-[5px] bg-red-400 rounded-t-lg sm:rounded-tr-none sm:rounded-l-lg"></div>
                <div class="p-4">
                    <div class="bg-green-100 text-green-600 p-2 rounded-full w-fit mb-4">
                        <span class="font-semibold text-sm">Guía rápida del sistema</span>
                    </div>

                    <div class="flex items-start space-x-3 mb-3">
                        <!-- Inicio -->
                        <svg class="h-7 w-7 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 9.75L12 3l9 6.75V20.25A1.5 1.5 0 0119.5 21H4.5A1.5 1.5 0 013 20.25V9.75z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V12h6v9" />
                        </svg>
                        <div>
                            <h6 class="font-semibold text-gray-800">Inicio:</h6>
                            <p class="text-gray-600 text-sm">Estas aquí.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 mb-3">
                        <!-- Lista -->
                        <svg class="h-7 w-7 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        <div>
                            <h6 class="font-semibold text-gray-800">Lista:</h6>
                            <p class="text-gray-600 text-sm">Visualiza una lista completa de tutores y tutorados.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 mb-3">
                                                    <!-- Agregar -->
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
                        <div>
                            <h6 class="font-semibold text-gray-800">Registrar:</h6>
                            <p class="text-gray-600 text-sm">Aquí podrás registrar a un nuevo usuario en MiTutor</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <!-- Citas -->
                        <svg class="h-8 w-8 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 2.25V4.5M18 2.25V4.5M3.75 8.25h16.5M4.5 6.75h15a1.5 1.5 0 011.5 1.5v11.25A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 19.5V8.25a1.5 1.5 0 011.5-1.5z" />
                        </svg>
                        <div>
                            <h6 class="font-semibold text-gray-800">Citas:</h6>
                            <p class="text-gray-600 text-sm">Un registro completo para ver todas las citas de los tutorados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Información de Mirtutor -->
        <div class="bg-white rounded-lg shadow-md flex flex-col h-fit sm:flex-col w-full md:max-w-sm">
            <div class="w-full h-[5px] sm:h-auto sm:w-[5px] bg-red-400 rounded-t-lg sm:rounded-tr-none sm:rounded-l-lg"></div>
            <div class="p-4">
                <div class="bg-green-100 text-green-600 p-2 rounded-full w-fit mb-4">
                    <span class="font-semibold text-sm">Informacion de MiTutor</span>
                </div>
                <div class="flex items-start space-x-3 mb-3">
                    <div>
                        <p class="text-gray-600 text-lg">En MiTutor podrás como administrador podras realizar el seguimiento de los tutorados y tutores en una lista completa y checar su informacion, editarla y en caso de que un tutorado tenga una necesidad especial marcarla.</p>
                        <br><p class="text-gray-600 text-lg">Eres la persona encargada de agregar cuentas de usuario y registrarlas, asegurate de que la informacion coincida con la del tutor o turoado</p>
                        <br><p class="text-gray-600 text-lg">Podras ver las citas de todos los tutorados y validad si es que tiene una necesidad especial en caso de que los resultados de la cita asi lo requieran.</p>


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
