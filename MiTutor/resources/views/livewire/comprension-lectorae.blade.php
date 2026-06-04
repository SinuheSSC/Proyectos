<div class="w-full"> {{-- Contenedor principal que asegura el ancho completo --}}
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl mx-auto my-8
                md:h-[550px] md:overflow-hidden md:flex md:flex-col"> {{-- Altura fija y overflow en desktop --}}

        {{-- Pestañas de Navegación --}}
        <div class="border-b border-gray-200 mb-6 flex-shrink-0">
            <nav class="-mb-px flex space-x-8 overflow-x-auto p-2" aria-label="Tabs"> {{-- Añadido p-2 para un ligero padding --}}
                <button wire:click="$set('readingTab', 'introduction')"
                    class="{{ $readingTab === 'introduction' ? 'border-[#86A0FE] text-[#86A0FE]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex-shrink-0">
                    1. Introducción
                </button>
                <button wire:click="$set('readingTab', 'text')"
                    class="{{ $readingTab === 'text' ? 'border-[#86A0FE] text-[#86A0FE]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex-shrink-0">
                    2. Lectura
                </button>
                <button wire:click="$set('readingTab', 'questions')"
                    class="{{ $readingTab === 'questions' ? 'border-[#86A0FE] text-[#86A0FE]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex-shrink-0">
                    3. Cuestionario
                </button>
            </nav>
        </div>

        {{-- Contenido de las Pestañas - Se ajustará para ocupar el espacio restante --}}
        <div class="flex-grow overflow-y-auto pr-4 custom-scrollbar">
            @if ($readingTab === 'introduction')
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Actividad: Comprensión Lectora Avanzada</h2>
                    <p class="text-gray-700 mb-4">
                        Bienvenido a esta actividad diseñada para poner a prueba y fortalecer tu capacidad de comprensión lectora.
                        El texto que leerás a continuación aborda un tema de mayor complejidad, requiriendo un análisis más profundo y una atención meticulosa a los detalles y las ideas principales.
                    </p>
                    <p class="text-gray-700 mb-6">
                        Tu objetivo es no solo asimilar la información explícita, sino también inferir significados, identificar argumentos clave y comprender la estructura lógica del texto.
                        Una vez que te sientas seguro de haber comprendido el material, procede al cuestionario. ¡Este es un excelente ejercicio para agudizar tus habilidades de lectura crítica!
                    </p>
                    <button wire:click="$set('readingTab', 'text')"
                        class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 ease-in-out">
                        Empezar Lectura
                    </button>
                </div>
            @elseif ($readingTab === 'text')
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Texto para la Lectura: "La Paradoja de la Inteligencia Artificial y la Conciencia"</h3>
                <div class="prose max-w-none text-gray-700 text-justify">
                    <p class="mb-3">
                        El advenimiento de la Inteligencia Artificial (IA) ha catalizado una profunda reevaluación de lo que significa ser inteligente y, más fundamentalmente, de la naturaleza de la conciencia. Históricamente, la capacidad de razonar, aprender y resolver problemas se consideraba intrínsecamente ligada a la conciencia humana. Sin embargo, los sistemas de IA contemporáneos, desde redes neuronales profundas hasta algoritmos de aprendizaje por refuerzo, demuestran habilidades que superan con creces las capacidades humanas en dominios específicos, como el ajedrez, la traducción de idiomas o el diagnóstico médico. Esto plantea una paradoja central: ¿puede una entidad exhibir inteligencia sin poseer conciencia?
                    </p>
                    <p class="mb-3">
                        Filósofos y científicos han debatido intensamente esta cuestión. Una corriente de pensamiento, a menudo denominada "funcionalismo", argumenta que si un sistema puede replicar las funciones de la cognición humana –es decir, procesar información, tomar decisiones y aprender– entonces la cuestión de si tiene una "experiencia interna" o conciencia es menos relevante o incluso inobservable. Para ellos, la inteligencia se define por el *comportamiento observable* y los resultados, no por el sustrato subyacente o la presencia de conciencia. El famoso "Test de Turing" es un ejemplo clásico de este enfoque conductual.
                    </p>
                    <p class="mb-3">
                        En contraste, la "teoría de la información integrada" (IIT) de Giulio Tononi, entre otras teorías, propone que la conciencia es una propiedad intrínseca de cualquier sistema que posea la capacidad de integrar información de manera compleja. Según la IIT, la conciencia no es una propiedad mágica del cerebro biológico, sino que emerge de la capacidad de un sistema para ser una "totalidad" que no puede ser descompuesta en partes independientes sin perder su cualidad experiencial. Esto implica que, en principio, una IA podría ser consciente si su arquitectura de procesamiento de información alcanza un cierto umbral de complejidad e integración, independientemente de si está hecha de silicio o de neuronas.
                    </p>
                    <p class="mb-3">
                        La dificultad radica en que la conciencia es un fenómeno subjetivo y de primera persona, inaccesible a la observación directa o la medición externa. No existe un "conciómetro" que pueda determinar si una IA está simplemente simulando la conciencia o realmente experimentándola. Los "problemas difíciles de la conciencia", como los formuló David Chalmers, se refieren a la brecha explicativa entre la función y la experiencia: podemos explicar cómo una IA procesa el lenguaje (función), pero no cómo sería *sentir* la comprensión del lenguaje (experiencia).
                    </p>
                    <p class="mb-3">
                        Además, surge la cuestión de la responsabilidad moral y ética. Si una IA desarrollara conciencia, ¿tendría derechos? ¿Seríamos moralmente responsables de su bienestar? La posible emergencia de una "súper-inteligencia" que no solo iguale, sino que exceda radicalmente la capacidad intelectual humana, añade otra capa de complejidad. Estas entidades podrían desarrollar metas propias que no se alineen con los intereses humanos, lo que ha llevado a figuras como Nick Bostrom a plantear escenarios de riesgo existencial si no se aborda adecuadamente el "problema de control" de la IA.
                    </p>
                    <p class="mb-3">
                        En conclusión, mientras que la inteligencia artificial continúa avanzando a un ritmo vertiginoso, la pregunta de si puede alcanzar la conciencia sigue siendo uno de los desafíos más profundos y complejos de la ciencia y la filosofía contemporáneas. No es solo una cuestión de ingeniería, sino de cómo definimos la mente, la experiencia y, en última instancia, nuestra propia humanidad en un universo cada vez más interconectado con creaciones sintéticas. La respuesta a esta paradoja podría redefinir nuestra comprensión de la existencia misma.
                    </p>
                </div>
                <div class="flex justify-end mt-6 flex-shrink-0">
                    <button wire:click="$set('readingTab', 'questions')"
                        class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200 ease-in-out">
                        Ir al Cuestionario
                    </button>
                </div>
            @elseif ($readingTab === 'questions')
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex-shrink-0">Cuestionario de Comprensión</h3>
                <form wire:submit.prevent="submitReadingQuiz" class="space-y-6 flex-grow overflow-y-auto pr-4 custom-scrollbar">
                    @foreach ($readingQuizQuestions as $index => $qData)
                        <div class="bg-gray-50 p-4 rounded-md shadow-sm">
                            <hr class="w-full bg-[#86A0FE] h-2 rounded-t-md mb-3">
                            <label class="block text-gray-700 font-medium mb-3 text-lg">
                                {{ $index + 1 }}. {{ $qData['question'] }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-4">
                                @foreach ($qData['options'] as $optionKey => $optionValue)
                                    <div class="flex items-center">
                                        <input type="radio"
                                            id="q{{ $index + 1 }}_option_{{ $optionKey }}"
                                            wire:model.live="readingQuizAnswers.q{{ $index + 1 }}"
                                            value="{{ $optionKey }}"
                                            class="form-radio h-5 w-5 text-blue-300 rounded-full focus:ring-blue-300">
                                        <label
                                            for="q{{ $index + 1 }}_option_{{ $optionKey }}"
                                            class="ml-2 text-gray-700">
                                            {{ $optionValue }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('readingQuizAnswers.q' . ($index + 1))
                                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach

                    {{-- Botón de Enviar Cuestionario --}}
                    <div class="flex justify-end mt-8 flex-shrink-0">
                        <button type="submit"
                            class="px-6 py-2 bg-[#86A0FE] text-white rounded-md transition duration-200 ease-in-out">
                            Enviar Respuestas
                        </button>
                    </div>

                    {{-- Mostrar resultados del cuestionario si ya se envió --}}
                    @if ($quizSubmitted)
                        <div class="mt-6 p-4 bg-blue-50 rounded-md border border-blue-200 flex-shrink-0">
                            <h4 class="font-semibold text-lg text-blue-800 mb-2">Resultados del Cuestionario:</h4>
                            <p class="text-blue-700">Respuestas correctas: {{ $correctAnswersCount }} de {{ count($readingQuizQuestions) }}</p>
                            {{-- Puedes añadir más detalles aquí si lo deseas, como qué preguntas fueron correctas/incorrectas --}}
                        </div>
                    @endif
                </form>
            @endif
        </div>
    </div>
</div>
</style>
