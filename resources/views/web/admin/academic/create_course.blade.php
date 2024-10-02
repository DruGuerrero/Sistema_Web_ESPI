@extends('adminlte::page')

@section('title', 'Agregar Curso')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="flex justify-between px-2.5 py-2">
        <h1>Agregar Curso</h1>
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
            <form action="{{ route('admin.academic.store_course') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-1">
                    <div class="form-group">
                        <label for="fullname" class="block font-medium text-gray-700">Nombre:</label>
                        <input type="text" name="fullname" placeholder= "Nombre del curso" class="form-control mt-1 block w-full" required>
                    </div>

                    <div class="form-group">
                        <label for="description" class="block font-medium text-gray-700">Descripción:</label>
                        <textarea name="description" class="form-control mt-1 block w-full" rows="5" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image" class="block font-medium text-gray-700">Imagen del Curso:</label>
                        <input type="file" name="image" class="form-control mt-1 block w-full" required>
                    </div>

                    <div class="form-group">
                        <label for="teacher" class="block font-medium text-gray-700">Docente:</label>
                        <select name="teacher" class="form-control mt-1 block w-full" required>
                            <option value="">Seleccione un docente</option>
                            @foreach($dbTeachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->moodleuser }})</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="subcategory_id" value="{{ $subcategory_id }}">

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.academic.show_subcategory', ['id' => $subcategory_id]) }}" class="btn btn-secondary mr-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop