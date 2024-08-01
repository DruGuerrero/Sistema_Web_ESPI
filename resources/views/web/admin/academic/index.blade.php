@extends('adminlte::page')

@section('title', 'Gestión Académica')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>Carreras</h1>
        <a href="{{ route('admin.academic.create') }}" class="btn btn-primary mb-3">Agregar carrera</a>
    </div>
    <hr>
@stop

@section('content')
    <div class="row flex flex-wrap justify-center items-center">
        @foreach($careers as $career)
            <div class="col-md-4 mb-4">
                <x-simple-card
                    title="{{ $career->nombre }}"
                    subtitle="{{ $career->cant_estudiantes }} estudiantes"
                    content="{{ $career->descripcion }}"
                    link="{{ route('admin.academic.show', ['id' => $career->id]) }}"
                    linkText="Ver detalles"
                />
            </div>
        @endforeach
    </div>
@stop