@extends('adminlte::page')

@section('title', 'Detalles del Estudiante')

@section('content_header')
    <h1>{{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <img src="{{ $photoUrl }}" alt="Foto tipo carnet" class="img-thumbnail">
            <p><strong>Número de carnet:</strong> {{ $student->num_carnet }}</p>
            <p><strong>E-mail:</strong> {{ $student->email }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_domicilio }}</p>
            <p><strong>Número de celular:</strong> {{ $student->num_celular }}</p>
            <p><strong>Usuario de moodle:</strong> (Generado más adelante)</p>
            <p><strong>Contraseña de moodle:</strong> (Generado más adelante)</p>
            <p><strong>Matriculado:</strong> {{ $student->matricula }}</p>

            <hr>

            <h5>Datos del tutor</h5>
            <p><strong>Nombre completo:</strong> {{ $student->nombre_tutor }}</p>
            <p><strong>Número de celular:</strong> {{ $student->celular_tutor }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_tutor }}</p>
            <p><strong>Parentesco:</strong> {{ $student->parentesco }}</p>

            <hr>

            <h5>Archivos del estudiante</h5>
            @if($files->isEmpty())
                <p>No hay archivos guardados para este estudiante.</p>
            @else
                <ul>
                    @foreach($files as $file)
                        <li>
                            <a href="{{ route('admin.students.download', $file->id) }}" class="btn btn-primary">
                                Descargar {{ $file->type }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary">Editar</a>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-body img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
@stop
