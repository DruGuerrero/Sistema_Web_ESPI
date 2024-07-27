@extends('adminlte::page')

@section('title', $course->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $course->nombre }}</h1>
        <a href="{{ route('admin.academic.edit_course', ['id' => $course->id]) }}" class="btn btn-primary mb-3">Editar Curso</a>
    </div>
    <hr>
@stop

@section('content')
    <div>
        <p>{{ $course->descripcion }}</p>
    </div>
    @php
        $headers = ['N°', 'Nombre', 'Promedio'];
        $rows = $students->map(function ($student, $index) {
            return [
                $index + 1,
                $student['fullname'],
                $student['average_grade'],
            ];
        })->toArray();
    @endphp

    <x-table :headers="$headers" :rows="$rows" />
    <a href="{{ route('admin.academic.show_subcategory', ['id' => $course->id_year]) }}" class="btn btn-primary">Volver</a>
@stop