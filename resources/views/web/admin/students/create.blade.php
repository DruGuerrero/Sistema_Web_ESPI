@extends('adminlte::page')

@section('title', 'Registrar Estudiante')

@section('content_header')
    <h1>Registrar Estudiante</h1>
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

    <form action="{{ route('admin.students.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="form-group">
            <label for="apellido_paterno">Apellido paterno:</label>
            <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}" required>
        </div>
        <div class="form-group">
            <label for="apellido_materno">Apellido materno:</label>
            <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}" required>
        </div>
        <div class="form-group">
            <label for="num_carnet">Número de carnet:</label>
            <input type="number" name="num_carnet" class="form-control" value="{{ old('num_carnet') }}" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="ciudad_domicilio">Ciudad de domicilio:</label>
            <input type="text" name="ciudad_domicilio" class="form-control" value="{{ old('ciudad_domicilio') }}" required>
        </div>
        <div class="form-group">
            <label for="num_celular">Número de celular:</label>
            <input type="number" name="num_celular" class="form-control" value="{{ old('num_celular') }}" required>
        </div>
        <div class="form-group">
            <label for="nombre_tutor">Nombre del tutor:</label>
            <input type="text" name="nombre_tutor" class="form-control" value="{{ old('nombre_tutor') }}" nullable>
        </div>
        <div class="form-group">
            <label for="celular_tutor">Número de celular del tutor:</label>
            <input type="number" name="celular_tutor" class="form-control" value="{{ old('celular_tutor') }}" nullable>
        </div>
        <div class="form-group">
            <label for="ciudad_tutor">Ciudad del tutor:</label>
            <input type="text" name="ciudad_tutor" class="form-control" value="{{ old('ciudad_tutor') }}" nullable>
        </div>
        <div class="form-group">
            <label for="parentesco">Parentesco:</label>
            <input type="text" name="parentesco" class="form-control" value="{{ old('parentesco') }}" nullable>
        </div>
        <!-- Campos de archivos pero no funcionales por ahora -->
        <div class="form-group">
            <label for="carnet_escaneado">Carnet escaneado:</label>
            <input type="file" name="carnet_escaneado" class="form-control" disabled>
        </div>
        <div class="form-group">
            <label for="foto_tipo_carnet">Foto tipo carnet:</label>
            <input type="file" name="foto_tipo_carnet" class="form-control" disabled>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-success">Registrar</button>
    </form>
@stop