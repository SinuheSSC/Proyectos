@extends('layouts.app')

@section('content')
    <div class="p-6">
        {{-- Mostrar el componente Livewire --}}
        @livewire(\App\Http\Livewire\TestComponent::class)
        </div>
@endsection
