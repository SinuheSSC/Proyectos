<x-layouts.tutor>
    <div class="flex flex-col items-center mt-20">
        @livewire('tutor-fecha-selector')

        <div class="w-5/6 h-[530px] mt-1  flex-shrink-0 flex flex-col items-center">
            @livewire('tutor-asistencia-card', ['grupoId' => $grupoId])
        </div>
    </div>
    </x-guest-layout>
