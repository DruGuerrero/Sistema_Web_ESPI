@extends('adminlte::page')

@section('title', 'Gestión Académica')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>Carreras</h1>
        <a href="#" class="btn btn-primary">Agregar carrera</a>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        @foreach($careers as $career)
            <div class="col-md-4 mb-4">
                <x-simple-card
                    title="{{ $career['name'] }}"
                    subtitle="{{ $career['students'] }} estudiantes"
                    content="{{ $career['description'] }}"
                    link="#"
                    linkText="Ver detalles"
                />
            </div>
        @endforeach
    </div>
@stop
