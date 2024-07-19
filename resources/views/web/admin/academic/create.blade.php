@extends('adminlte::page')

@section('title', 'Agregar carrera')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Agregar carrera</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.academic.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group d-flex justify-content-end">
            <a href="{{ route('admin.academic.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </form>
@stop
