@extends('adminlte::page')

@section('title', 'Agregar Carrera')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Agregar Carrera</h1>
    </div>
    <hr>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mx-auto py-5">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.academic.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-1">
                    <div class="form-group">
                        <label for="name" class="block font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="name" placeholder="Nombre de la carrera" class="form-control mt-1 block w-full" required>
                    </div>

                    <div class="form-group">
                        <label for="description" class="block font-medium text-gray-700">Descripción:</label>
                        <textarea name="description" class="form-control mt-1 block w-full" rows="5" required></textarea>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.academic.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop