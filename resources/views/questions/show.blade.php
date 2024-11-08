@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalles de la Pregunta</h1>

    <div class="card">
        <div class="card-header">
            Información de la Pregunta
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $question->question_id }}</p>
            <p><strong>Título:</strong> {{ $question->title }}</p>
            <p><strong>Enlace:</strong> <a href="{{ $question->link }}" target="_blank">{{ $question->link }}</a></p>
            <p><strong>Etiquetas:</strong> {{ $question->tags }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $question->creation_date }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('questions.index') }}" class="btn btn-primary">Volver a la lista</a>
        </div>
    </div>
</div>
@endsection
