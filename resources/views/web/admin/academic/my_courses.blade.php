@extends('adminlte::page')

@section('title', 'Mis Cursos')

@section('content_header')
    <h1>Mis Cursos Asignados</h1>
    <hr>
@stop

@section('content')
    @if($courses->isEmpty())
        <p>No tienes cursos asignados.</p>
    @else
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-4 mb-4">
                    <x-simple-card
                        title="{{ $course->nombre }}"
                        subtitle="{{ $course->year->nombre }}"
                        content="{{ $course->descripcion }}"
                        link="{{ route('admin.academic.show_course', ['id' => $course->id]) }}"
                        linkText="Ver Curso"
                    />
                </div>
            @endforeach
        </div>
    @endif
@stop