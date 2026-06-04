@props(['icon', 'text', 'route', 'expanded'])

@php
    $active = request()->is(ltrim(parse_url($route, PHP_URL_PATH), '/'));
@endphp

<a href="{{ $route }}" class="w-full flex justify-center">
    <button class="flex items-center gap-3 w-4/5 px-4 py-2 text-lg rounded-md transition-all duration-300
        {{ $active ? 'bg-green-100 text-green-500' : 'text-black hover:bg-gray-100' }}">

        <x-icon :name="$icon" class="w-10 h-10 text-gray-700" />

        @if($expanded)
            <span class="whitespace-nowrap text-xl">{{ $text }}</span>
        @endif
    </button>
</a>
