<div>
    <!-- ENCABEZADO -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-blue-400 text-lg font-semibold mb-4">Examen vocacional</h2>

        <!-- Indicador de progreso -->
        <div class="text-right text-gray-500 mb-4">
            {{ $currentStep }} / 4
        </div>

        <!-- SECCIÓN 1 -->
        @if ($currentStep === 1)
<div class="space-y-4">
    @foreach (range(1,5) as $i)
        <div>
            <p class="font-medium mb-1">
                @switch($i)
                    @case(1) 1. ¿Qué tipo de actividades disfrutas más en tu tiempo libre? @break
                    @case(2) 2. Si tuvieras un día libre para hacer lo que quisieras, ¿qué harías? @break
                    @case(3) 3. ¿Cómo te sientes al hablar en público? @break
                    @case(4) 4. ¿Te gusta trabajar con computadoras y tecnología? @break
                    @case(5) 5. ¿Prefieres resolver problemas matemáticos o escribir ensayos? @break
                @endswitch
            </p>
            <select wire:model="respuestas.{{ $i }}" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">--Seleccione una opción--</option>
                @switch($i)
                    @case(1)
                        <option>Deporte</option>
                        <option>Arte</option>
                        <option>Lectura</option>
                        @break
                    @case(2)
                        <option>Viajar</option>
                        <option>Estudiar</option>
                        <option>Descansar</option>
                        @break
                    @case(3)
                        <option>Cómodo</option>
                        <option>Nervioso</option>
                        <option>Lo evito</option>
                        @break
                    @case(4)
                        <option>Sí</option>
                        <option>No</option>
                        @break
                    @case(5)
                        <option>Matemáticos</option>
                        <option>Ensayos</option>
                        @break
                @endswitch
            </select>
        </div>
    @endforeach
</div>
@endif


        <!-- SECCIÓN 2 -->
        @if ($currentStep === 2)
<div class="space-y-4">
    @foreach (range(6,10) as $i)
        <div>
            <p class="font-medium mb-1">
                @switch($i)
                    @case(6) 6. ¿Cómo te sientes al analizar datos o resolver problemas lógicos? @break
                    @case(7) 7. ¿Qué tan bien se te da trabajar en equipo? @break
                    @case(8) 8. ¿Qué tipo de tareas disfrutas más? @break
                    @case(9) 9. ¿Te gustaría trabajar en un laboratorio haciendo investigaciones? @break
                    @case(10) 10. ¿Qué prefieres: Diseñar una campaña publicitaria o escribir un código de software? @break
                @endswitch
            </p>
            <select wire:model="respuestas.{{ $i }}" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">--Seleccione una opción--</option>
                @switch($i)
                    @case(6)
                        <option>Muy cómodo</option>
                        <option>Algo cómodo</option>
                        <option>No me gusta mucho</option>
                        @break
                    @case(7)
                        <option>Muy bien</option>
                        <option>Regular</option>
                        <option>No mucho</option>
                        @break
                    @case(8)
                        <option>Tareas manuales</option>
                        <option>Tareas analíticas</option>
                        <option>Tareas creativas</option>
                        @break
                    @case(9)
                        <option>Sí, mucho</option>
                        <option>Me da igual</option>
                        <option>No me interesa</option>
                        @break
                    @case(10)
                        <option>Diseñar una campaña publicitaria</option>
                        <option>Escribir un código de software</option>
                        <option>Ambos me interesan</option>
                        @break
                @endswitch
            </select>
        </div>
    @endforeach
</div>
@endif

        @if ($currentStep === 3)
<div class="space-y-4">
    @foreach (range(11,15) as $i)
        <div>
            <p class="font-medium mb-1">
                @switch($i)
                    @case(11) 11. ¿Qué es lo más importante para ti en una carrera profesional? @break
                    @case(12) 12. ¿En qué ambiente de trabajo te sentirías más cómodo? @break
                    @case(13) 13. ¿Qué tipo de problemas te gustaría resolver en el futuro? @break
                    @case(14) 14. ¿Prefieres un trabajo con horarios fijos o uno con más flexibilidad? @break
                    @case(15) 15. ¿Cómo te gustaría que fuera tu día de trabajo ideal? @break
                @endswitch
            </p>
            <select wire:model="respuestas.{{ $i }}" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">--Seleccione una opción--</option>
                @switch($i)
                    @case(11)
                        <option>Salario competitivo</option>
                        <option>Estabilidad laboral</option>
                        <option>Crecimiento profesional</option>
                        @break
                    @case(12)
                        <option>En equipo</option>
                        <option>Independiente</option>
                        <option>Flexible</option>
                        @break
                    @case(13)
                        <option>Técnicos</option>
                        <option>Humanos o sociales</option>
                        <option>Creativos</option>
                        @break
                    @case(14)
                        <option>Horarios fijos</option>
                        <option>Flexibilidad horaria</option>
                        @break
                    @case(15)
                        <option>Con rutinas definidas</option>
                        <option>Con variedad de actividades</option>
                        <option>En constante cambio</option>
                        @break
                @endswitch
            </select>
        </div>
    @endforeach
</div>
@endif

       @if ($currentStep === 4)
<div class="space-y-4">
    @foreach (range(16,20) as $i)
        <div>
            <p class="font-medium mb-1">
                @switch($i)
                    @case(16) 16. ¿Cuál de estas materias disfrutas más? @break
                    @case(17) 17. ¿Te gustaría trabajar en salud o ciencias médicas? @break
                    @case(18) 18. ¿Qué tipo de herramientas disfrutas más usar? @break
                    @case(19) 19. Si tuvieras que elegir, ¿Qué prefieres? @break
                    @case(20) 20. ¿Te gustaría liderar un equipo de trabajo? @break
                @endswitch
            </p>
            <select wire:model="respuestas.{{ $i }}" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="">--Seleccione una opción--</option>
                @switch($i)
                    @case(16)
                        <option>Matemáticas</option>
                        <option>Lengua y Literatura</option>
                        <option>Ciencias Naturales</option>
                        @break
                    @case(17)
                        <option>Sí, me interesa</option>
                        <option>No mucho</option>
                        <option>Indeciso</option>
                        @break
                    @case(18)
                        <option>Herramientas manuales</option>
                        <option>Computadoras y software</option>
                        <option>Materiales artísticos</option>
                        @break
                    @case(19)
                        <option>Trabajar con personas</option>
                        <option>Trabajar con datos</option>
                        <option>Trabajar con cosas</option>
                        @break
                    @case(20)
                        <option>Sí</option>
                        <option>No</option>
                        <option>Tal vez</option>
                        @break
                @endswitch
            </select>
        </div>
    @endforeach
</div>
@endif


        <!-- Botones -->
        <div class="flex justify-end gap-2 mt-6">
            @if ($currentStep == 1)
                <button onclick="window.location='{{ route('examenv') }}'" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                    Salir
                </button>
                <button wire:click="nextStep" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Siguiente
                </button>
            @elseif ($currentStep > 1 && $currentStep < 4)
                <button wire:click="prevStep" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                    Regresar
                </button>
                <button wire:click="nextStep" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Siguiente
                </button>
            @elseif ($currentStep === 4)
                <button wire:click="prevStep" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                    Regresar
                </button>
                <button wire:click="finishExam" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Terminar
                </button>
            @endif
        </div>


    </div>
</div>
