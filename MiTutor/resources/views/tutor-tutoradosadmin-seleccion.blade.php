@extends('components.layouts.layout')

@section('title', 'Inicio')

@section('content')
    {{-- <!--<livewire:selector-vista />--> --}}
    @livewire('lista-tutores-tutorados')
@endsection
