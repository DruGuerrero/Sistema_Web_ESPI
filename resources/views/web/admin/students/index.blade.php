@extends('adminlte::page')

@section('title', 'Lista de Estudiantes')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Lista de Estudiantes</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulario de búsqueda y filtrado -->
    <form action="{{ route('admin.students.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar" value="{{ request()->input('search') }}">
        </div>
        <div class="form-group mr-2">
            <select name="filter" class="form-control">
                <option value="">Mostrar todos</option>
                <option value="Matriculado" {{ request()->input('filter') == 'Matriculado' ? 'selected' : '' }}>Matriculado</option>
                <option value="No matriculado" {{ request()->input('filter') == 'No matriculado' ? 'selected' : '' }}>No matriculado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <a href="{{ route('admin.students.create') }}" class="btn btn-primary mb-3">Registrar nuevo</a>

    @php
        $headers = ['N°', 'Nombre', 'Matricula', 'Estado', 'Acciones'];
        $rows = $students->map(function ($student, $index) use ($students) {
            return [
                $students->firstItem() + $index,
                $student->nombre . ' ' . $student->apellido_paterno . ' ' . $student->apellido_materno,
                $student->matricula,
                $student->disabled ? 'Deshabilitado' : 'Habilitado',
                view('components.button-preline', [
                    'attributes' => new \Illuminate\View\ComponentAttributeBag(['onclick' => "window.location='".route('admin.students.show', $student->id)."'"]),
                    'slot' => 'Ver detalles'
                ])->render()
            ];
        })->toArray();
    @endphp

    <x-table :headers="$headers" :rows="$rows" />

    <!-- Agregar paginación -->
    <x-pagination :paginator="$students" />
@stop