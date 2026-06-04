@php
    $grupoId = request('grupo'); // toma el valor de ?grupo=XX en la URL
@endphp

<x-layouts.tutor>
    <div class="lg:ms-20 mt-[70px]">
        @livewire('tutor-tutorados-view', ['grupo' => $grupoId])
    </div>
</x-layouts.tutor>

