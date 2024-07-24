@extends('adminlte::page')

@section('title', $career->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $career->nombre }}</h1>
        <a href="{{ route('admin.academic.create_year', ['career_id' => $career->id]) }}" class="btn btn-primary mb-3">Agregar año académico</a>
    </div>
    <hr>
@stop

@section('content')
    <p class="py-0.5">{{ strip_tags($career->descripcion) }}</p>
    <div class="row">
        @foreach($career->years as $year)
            <div class="col-md-6 mb-4">
                <x-advanced-card
                    title="{{ $year->nombre }}"
                    content="{{ strip_tags($year->descripcion) }}"
                    :contentBlocks="$year->courses->map(function($course) {
                        return [
                            'name' => $course->nombre,
                            'professor' => $course->docente->name,
                        ];
                    })->toArray()"
                    leftButtonLink="#"
                    leftButtonText="Eliminar"
                    rightButtonLink="{{ route('admin.academic.show_subcategory', ['id' => $year->id]) }}"
                    rightButtonText="Ver"
                />
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.index') }}" class="btn btn-primary">Volver</a>
@stop