<!-- resources/views/questions/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Listado de Preguntas</h1>

        <!-- Botón para actualizar preguntas -->
        <a href="{{ route('questions.api.update') }}" class="btn btn-primary mb-3">Actualizar Preguntas via API</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabla de preguntas -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Enlace</th>
                    <th>Etiquetas</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                    <tr>
                        <td>{{ $question->question_id }}</td>
                        <td>{{ $question->title }}</td>
                        <td><a href="{{ $question->link }}" target="_blank">Ver pregunta</a></td>
                        <td>{{ $question->tags }}</td>
                        <td>{{ $question->creation_date }}</td>
                        <td>
                            <a href="{{ route('questions.show', $question->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $question->id }}').submit();">Eliminar</a>

                            <form id="delete-form-{{ $question->id }}" action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $questions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
