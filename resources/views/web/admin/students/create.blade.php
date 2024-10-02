@extends('adminlte::page')

@section('title', 'Registrar Estudiante')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Registrar Estudiante</h1>
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
            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-2"> {{-- Cambié el gap a 2 en lugar de 6 --}}

                    {{-- Fila: Nombre, Apellido Paterno y Apellido Materno --}}
                    <div class="grid grid-cols-3 gap-2"> {{-- También ajustamos el gap aquí --}}
                        <div class="form-group">
                            <label for="nombre" class="block font-medium text-gray-700">Nombre:</label>
                            <input type="text" name="nombre" class="form-control block w-full" value="{{ old('nombre') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_paterno" class="block font-medium text-gray-700">Apellido Paterno:</label>
                            <input type="text" name="apellido_paterno" class="form-control block w-full" value="{{ old('apellido_paterno') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_materno" class="block font-medium text-gray-700">Apellido Materno:</label>
                            <input type="text" name="apellido_materno" class="form-control block w-full" value="{{ old('apellido_materno') }}" required>
                        </div>
                    </div>

                    {{-- Fila: Número de Carnet y Email --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group">
                            <label for="num_carnet" class="block font-medium text-gray-700">Número de Carnet:</label>
                            <input type="text" name="num_carnet" placeholder="Ej: 1234567" class="form-control block w-full" value="{{ old('num_carnet') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="block font-medium text-gray-700">E-mail:</label>
                            <input type="email" name="email" placeholder="ejemplo@ejemplo.com" class="form-control block w-full" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    {{-- Fila: Ciudad de Domicilio y Número de Celular --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group">
                            <label for="ciudad_domicilio" class="block font-medium text-gray-700">Ciudad de Domicilio:</label>
                            <input type="text" name="ciudad_domicilio" class="form-control block w-full" value="{{ old('ciudad_domicilio') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="num_celular" class="block font-medium text-gray-700">Número de Celular:</label>
                            <input type="text" name="num_celular" placeholder="Ej: 12345678" class="form-control block w-full" value="{{ old('num_celular') }}" required>
                        </div>
                    </div>

                    {{-- Fila: Carrera --}}
                    <div class="form-group">
                        <label for="career" class="block font-medium text-gray-700">Carrera:</label>
                        <select name="career_id" class="form-control block w-full" required>
                            @foreach ($careers as $career)
                                <option value="{{ $career->id }}">{{ $career->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sección: Datos del Tutor --}}
                    <div class="py-3 flex items-center text-s text-neutral-950 after:flex-1 after:border-t after:border-neutral-950 after:ms-6 text-2xl">Datos del Tutor</div>

                    {{-- Fila: Nombre del Tutor y Celular del Tutor --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group">
                            <label for="nombre_tutor" class="block font-medium text-gray-700">Nombre del Tutor:</label>
                            <input type="text" name="nombre_tutor" class="form-control block w-full" value="{{ old('nombre_tutor') }}">
                        </div>
                        <div class="form-group">
                            <label for="celular_tutor" class="block font-medium text-gray-700">Número de Celular del Tutor:</label>
                            <input type="text" name="celular_tutor" placeholder="Ej: 12345678" class="form-control block w-full" value="{{ old('celular_tutor') }}">
                        </div>
                    </div>

                    {{-- Fila: Ciudad del Tutor y Parentesco --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group">
                            <label for="ciudad_tutor" class="block font-medium text-gray-700">Ciudad del Tutor:</label>
                            <input type="text" name="ciudad_tutor" class="form-control block w-full" value="{{ old('ciudad_tutor') }}">
                        </div>
                        <div class="form-group">
                            <label for="parentesco" class="block font-medium text-gray-700">Parentesco:</label>
                            <input type="text" name="parentesco" placeholder="Ej: Padre/Madre" class="form-control block w-full" value="{{ old('parentesco') }}">
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>    
@stop