@extends('adminlte::page')

@section('title', 'Agregar Curso')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Agregar Curso</h1>
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

    <form action="{{ route('admin.academic.store_course') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="fullname">Nombre del Curso:</label>
            <input type="text" name="fullname" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción:</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Imagen del Curso:</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="teacher">Docente:</label>
            <select name="teacher" class="form-control" required>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher['id'] }}">{{ $teacher['fullname'] }} ({{ $teacher['username'] }})</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="subcategory_id" value="{{ $subcategory_id }}">
        <div class="form-group d-flex justify-content-end">
            <a href="{{ route('admin.academic.show_subcategory', ['id' => $subcategory_id]) }}" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </form>
@stop