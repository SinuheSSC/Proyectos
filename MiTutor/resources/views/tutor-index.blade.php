<x-layouts.tutor>
    <div class="w-full flex flex-col lg:flex-row justify-center mt-20">
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center gap-6">
            @livewire('tutor-datos-generales-card')
            @livewire('tutor-guia-rapida-card')
        </div>
        <div class="w-full lg:w-1/3">
            @livewire('tutor-informacion-card')
        </div>
    </div>
</x-layouts.tutor>
