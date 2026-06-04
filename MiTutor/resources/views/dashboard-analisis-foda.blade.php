@extends('layouts.template-estudiante')

@section('content')
    <div class="lg:ms-20 mt-1 flex justify-center">
        <div class="w-full max-w-2xl">
            @livewire('analisis-fodae')
            {{-- Mensajes de notificación --}}
@if (session()->has('message'))
    <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-md">
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-md">
        {{ session('error') }}
    </div>
@endif

@if (session()->has('warning'))
    <div class="mt-4 p-3 bg-yellow-100 text-yellow-700 rounded-md">
        {{ session('warning') }}
    </div>
@endif

@if (session()->has('info'))
    <div class="mt-4 p-3 bg-blue-100 text-blue-700 rounded-md">
        {{ session('info') }}
    </div>
@endif
        </div>
    </div>
@endsection

