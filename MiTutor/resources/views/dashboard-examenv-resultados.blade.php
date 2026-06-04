@extends('layouts.template-estudiante')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Resultados del Examen Vocacional</h1>

    <div class="mb-4">
        <h2 class="text-lg font-semibold text-blue-600">Áreas de Especialidad:</h2>
        <p class="text-gray-700">{{ $examen->areasDeEspecialidad }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold text-green-600">Carreras Recomendadas:</h2>
        <ul class="list-disc list-inside text-gray-700">
            @foreach(explode(',', $examen->carrerasRecomendadas) as $carrera)
                <li>{{ trim($carrera) }}</li>
            @endforeach
        </ul>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold text-purple-600">Recomendaciones:</h2>
        <p class="text-gray-700">{{ $examen->recomendaciones }}</p>
    </div>
</div>
@endsection
