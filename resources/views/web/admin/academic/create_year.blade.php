@extends('adminlte::page')

@section('title', 'Agregar Año Académico')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Agregar Año Académico</h1>
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

    <form action="{{ route('admin.academic.store_year') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <input type="hidden" name="career_id" value="{{ $career_id }}">
        <div class="form-group d-flex justify-content-end">
            <a href="{{ route('admin.academic.show', ['id' => $career_id]) }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear</button>
        </div>
    </form>
@stop