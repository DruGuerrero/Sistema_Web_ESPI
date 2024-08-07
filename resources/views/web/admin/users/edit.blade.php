@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Editar usuario</h1>
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
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-4">
                    <label for="name" class="block font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" class="form-control mt-1 block w-full" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group mb-4">
                    <label for="email" class="block font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="form-control mt-1 block w-full" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="block font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" class="form-control mt-1 block w-full">
                    <small class="form-text text-muted">Para continuar con la contraseña actual, dejar este espacio en blanco.</small>
                </div>
                <div class="form-group mb-4">
                    <label for="password_confirmation" class="block font-medium text-gray-700">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control mt-1 block w-full">
                </div>
                <div class="form-group mb-4">
                    <label for="role" class="block font-medium text-gray-700">Rol</label>
                    <select name="role" class="form-control mt-1 block w-full" required>
                        <option value="Administrativo" {{ old('role', $user->role) == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                        <option value="Jefe de carrera" {{ old('role', $user->role) == 'Jefe de carrera' ? 'selected' : '' }}>Jefe de carrera</option>
                        <option value="Docente" {{ old('role', $user->role) == 'Docente' ? 'selected' : '' }}>Docente</option>
                        <option value="Superusuario" {{ old('role', $user->role) == 'Superusuario' ? 'selected' : '' }}>Superusuario</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label for="disabled" class="block font-medium text-gray-700">Habilitar</label>
                    <x-checkbox name="disabled" :checked="!$user->disabled"></x-checkbox>
                </div>
                <div class="flex justify-between mt-6">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>    
@stop