@extends('adminlte::page')

@section('title', 'Editar Año Académico')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Editar Año Académico</h1>
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

    <form action="{{ route('admin.academic.update_subcategory', ['id' => $year->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $year->nombre) }}" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $year->descripcion) }}</textarea>
        </div>
        <div class="form-group d-flex justify-content-end">
            <a href="{{ route('admin.academic.show_subcategory', ['id' => $year->id]) }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
@stop