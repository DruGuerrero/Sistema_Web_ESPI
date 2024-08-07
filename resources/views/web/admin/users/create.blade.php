@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Crear usuario</h1>
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
        <div class="max-w-lg mx-auto bg-white p-4 rounded-lg shadow-md">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" class="form-control" required>
                        <option value="Administrativo" {{ old('role') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                        <option value="Jefe de carrera" {{ old('role') == 'Jefe de carrera' ? 'selected' : '' }}>Jefe de carrera</option>
                        <option value="Docente" {{ old('role') == 'Docente' ? 'selected' : '' }}>Docente</option>
                        <option value="Superusuario" {{ old('role') == 'Superusuario' ? 'selected' : '' }}>Superusuario</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Crear</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>    
@stop
