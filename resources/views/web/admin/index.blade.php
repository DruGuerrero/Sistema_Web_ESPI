@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
@stop

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="text-center">
            <h1 class="display-4">Bienvenido, {{ strtok(auth()->user()->name, ' ') }}!</h1>
            <p class="lead">Bienvenido al panel administrativo del Instituto ESPI Bolivia.</p>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        /* Opcional: Asegura que el contenedor principal ocupe toda la altura disponible */
        .content-wrapper, .content {
            height: 100%;
        }

        /* Opcional: Ajusta el pie de página para que no interfiera con el contenido centrado */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stop
