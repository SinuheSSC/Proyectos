@extends('components.layouts.layout')

@section('title', 'Inicio')

@section('content')
    {{-- <!--<livewire:selector-vista />--> --}}
    @livewire('editar-perfil' , ['curp' => $curp])
@endsection
