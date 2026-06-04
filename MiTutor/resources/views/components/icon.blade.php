@props(['name'])

{{-- Puedes usar Heroicons, Lucide o FontAwesome --}}
@if ($name === 'home')
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m4 0h6" />
    </svg>
@endif

@if ($name === 'user')
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M5.121 17.804A6.97 6.97 0 0112 15c1.933 0 3.682.784 4.879 2.05M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
@endif