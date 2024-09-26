@extends('adminlte::page')

@section('title', 'Editar Estudiante')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Editar Estudiante</h1>
    </div>
    <hr>
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

    <div class="container mx-auto py-5">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-1">
                    <div class="form-group">
                        <label for="nombre" class="block font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="nombre" class="form-control mt-1 block w-full" value="{{ old('nombre', $student->nombre) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido_paterno" class="block font-medium text-gray-700">Apellido paterno:</label>
                        <input type="text" name="apellido_paterno" class="form-control mt-1 block w-full" value="{{ old('apellido_paterno', $student->apellido_paterno) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido_materno" class="block font-medium text-gray-700">Apellido materno:</label>
                        <input type="text" name="apellido_materno" class="form-control mt-1 block w-full" value="{{ old('apellido_materno', $student->apellido_materno) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="num_carnet" class="block font-medium text-gray-700">Número de carnet:</label>
                        <input type="text" name="num_carnet" class="form-control mt-1 block w-full" value="{{ old('num_carnet', $student->num_carnet) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="block font-medium text-gray-700">E-mail:</label>
                        <input type="email" name="email" class="form-control mt-1 block w-full" value="{{ old('email', $student->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="ciudad_domicilio" class="block font-medium text-gray-700">Ciudad de domicilio:</label>
                        <input type="text" name="ciudad_domicilio" class="form-control mt-1 block w-full" value="{{ old('ciudad_domicilio', $student->ciudad_domicilio) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="num_celular" class="block font-medium text-gray-700">Número de celular:</label>
                        <input type="text" name="num_celular" class="form-control mt-1 block w-full" value="{{ old('num_celular', $student->num_celular) }}" required>
                    </div>

                    <div class="py-3 flex items-center text-s text-neutral-950 after:flex-1 after:border-t after:border-neutral-950 after:ms-6 text-2xl">Datos del tutor</div>

                    <div class="form-group">
                        <label for="nombre_tutor" class="block font-medium text-gray-700">Nombre del tutor:</label>
                        <input type="text" name="nombre_tutor" class="form-control mt-1 block w-full" value="{{ old('nombre_tutor', $student->nombre_tutor) }}">
                    </div>

                    <div class="form-group">
                        <label for="celular_tutor" class="block font-medium text-gray-700">Número de celular del tutor:</label>
                        <input type="text" name="celular_tutor" class="form-control mt-1 block w-full" value="{{ old('celular_tutor', $student->celular_tutor) }}">
                    </div>

                    <div class="form-group">
                        <label for="ciudad_tutor" class="block font-medium text-gray-700">Ciudad del tutor:</label>
                        <input type="text" name="ciudad_tutor" class="form-control mt-1 block w-full" value="{{ old('ciudad_tutor', $student->ciudad_tutor) }}">
                    </div>

                    <div class="form-group">
                        <label for="parentesco" class="block font-medium text-gray-700">Parentesco:</label>
                        <input type="text" name="parentesco" class="form-control mt-1 block w-full" value="{{ old('parentesco', $student->parentesco) }}">
                    </div>

                    <div class="form-group">
                        <label for="documentos_estudiante" class="block font-medium text-gray-700">Documentos del Estudiante:</label>
                        <input type="file" name="documentos_estudiante" class="form-control mt-1 block w-full">
                    </div>

                    <div class="form-group">
                        <label for="foto_tipo_carnet" class="block font-medium text-gray-700">Foto tipo carnet:</label>
                        <input type="file" name="foto_tipo_carnet" class="form-control mt-1 block w-full">
                    </div>

                    <div class="form-group mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="disabled" class="form-check-input mr-2" id="disabled" {{ $student->disabled ? '' : 'checked' }}>
                            <label class="form-check-label" for="disabled">Habilitar</label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </div>
            </form>

            <hr class="my-6">

            <h5 class="text-xl font-bold">Archivos del Estudiante</h5>
            @if($student->mediaFiles->isEmpty())
                <p>No hay archivos guardados para este estudiante.</p>
            @else
                <ul class="list-disc list-inside mt-4">
                    @foreach($student->mediaFiles as $file)
                        <li class="flex items-center mb-2">
                            <a href="{{ route('admin.students.download', $file->id) }}" class="btn btn-primary mr-2">
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
        </div>
    </div>
@stop