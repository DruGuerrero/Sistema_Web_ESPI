@extends('adminlte::page')

@section('title', 'Gestión Académica')

@section('content_header')
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
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $career['name'] }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $career['students'] }} estudiantes</h6>
                        <p class="card-text">{{ $career['description'] }}</p>
                        <a href="#" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
