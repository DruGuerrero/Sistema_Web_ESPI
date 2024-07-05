@extends('adminlte::page')

@section('title', 'Editar Estudiante')

@section('content_header')
    <h1>Editar Estudiante</h1>
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

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $student->nombre) }}" required>
        </div>
        <div class="form-group">
            <label for="apellido_paterno">Apellido paterno:</label>
            <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno', $student->apellido_paterno) }}" required>
        </div>
        <div class="form-group">
            <label for="apellido_materno">Apellido materno:</label>
            <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno', $student->apellido_materno) }}" required>
        </div>
        <div class="form-group">
            <label for="num_carnet">Número de carnet:</label>
            <input type="text" name="num_carnet" class="form-control" value="{{ old('num_carnet', $student->num_carnet) }}" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
        </div>
        <div class="form-group">
            <label for="ciudad_domicilio">Ciudad de domicilio:</label>
            <input type="text" name="ciudad_domicilio" class="form-control" value="{{ old('ciudad_domicilio', $student->ciudad_domicilio) }}" required>
        </div>
        <div class="form-group">
            <label for="num_celular">Número de celular:</label>
            <input type="text" name="num_celular" class="form-control" value="{{ old('num_celular', $student->num_celular) }}" required>
        </div>
        <div class="form-group">
            <label for="nombre_tutor">Nombre del tutor:</label>
            <input type="text" name="nombre_tutor" class="form-control" value="{{ old('nombre_tutor', $student->nombre_tutor) }}" nullable>
        </div>
        <div class="form-group">
            <label for="celular_tutor">Número de celular del tutor:</label>
            <input type="text" name="celular_tutor" class="form-control" value="{{ old('celular_tutor', $student->celular_tutor) }}" nullable>
        </div>
        <div class="form-group">
            <label for="ciudad_tutor">Ciudad del tutor:</label>
            <input type="text" name="ciudad_tutor" class="form-control" value="{{ old('ciudad_tutor', $student->ciudad_tutor) }}" nullable>
        </div>
        <div class="form-group">
            <label for="parentesco">Parentesco:</label>
            <input type="text" name="parentesco" class="form-control" value="{{ old('parentesco', $student->parentesco) }}" nullable>
        </div>
        <!-- Campos de archivos -->
        <div class="form-group">
            <label for="carnet_escaneado">Carnet escaneado:</label>
            <input type="file" name="carnet_escaneado" class="form-control">
        </div>
        <div class="form-group">
            <label for="foto_tipo_carnet">Foto tipo carnet:</label>
            <input type="file" name="foto_tipo_carnet" class="form-control">
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>

    <hr>

    <h5>Archivos del estudiante</h5>
    @if($student->mediaFiles->isEmpty())
        <p>No hay archivos guardados para este estudiante.</p>
    @else
        <ul>
            @foreach($student->mediaFiles as $file)
                <li>
                    <a href="{{ route('admin.students.download', $file->id) }}" class="btn btn-primary">
                        Descargar {{ $file->type }}
                    </a>
                    <form action="{{ route('admin.students.deleteFile', $file->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@stop
