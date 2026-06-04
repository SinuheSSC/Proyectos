@extends('components.layouts.layout')

@section('title', 'Inicio')

@section('content')
    {{-- <!--<livewire:selector-vista />--> --}}
    @livewire('admin-citas')
@endsection
