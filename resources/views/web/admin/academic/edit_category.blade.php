@extends('adminlte::page')

@section('title', 'Editar Carrera')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Editar Carrera</h1>
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

    <form action="{{ route('admin.academic.update_category', ['id' => $career->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $career->nombre) }}" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $career->descripcion) }}</textarea>
        </div>
        <div class="form-group d-flex justify-content-end">
            <a href="{{ route('admin.academic.show', ['id' => $career->id]) }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
@stop