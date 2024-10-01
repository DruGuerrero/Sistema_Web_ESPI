@extends('adminlte::page')

@section('title', 'Cambiar Contraseña')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Cambiar Contraseña</h1>
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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="container mx-auto py-5">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.users.updatePassword') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div class="form-group">
                        <label for="password" class="block font-medium text-gray-700">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control mt-1 block w-full" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="block font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control mt-1 block w-full" required>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="submit" class="btn btn-success">Actualizar Contraseña</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop