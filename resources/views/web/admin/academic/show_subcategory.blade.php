@extends('adminlte::page')

@section('title', $subcategory['name'])

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $subcategory['name'] }}</h1>
        <a href="#" class="btn btn-primary mb-3">Editar año académico</a>
    </div>
    <hr>
@stop


@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <p>{{ strip_tags($subcategory['description']) }}</p>
        </div>
        <div>
            <span>{{ $studentsCount }} estudiantes</span>
            <a href="#" class="btn btn-primary">Agregar curso</a>
        </div>
    </div>
    <div class="row">
        @foreach($coursesAndProfessors as $course)
            <div class="col-md-4 mb-4">
                <x-advanced-card
                    title="{{ $course['name'] }}"
                    image="{{ $course['image'] }}"
                    content="{{ $course['description'] }}"
                    :contentBlocks="[['name' => 'Profesor', 'professor' => $course['professor']]]"
                    leftButtonLink="#"
                    rightButtonLink="#"
                />
                {{-- Log para verificar los datos pasados al componente --}}
                @php
                    Log::info('Course Data Passed to Component:', [
                        'name' => $course['name'],
                        'image' => $course['image'],
                        'description' => $course['description'],
                        'professor' => $course['professor']
                    ]);
                @endphp
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.show', ['id' => $parentCategoryId]) }}" class="btn btn-primary">Volver</a>
@stop