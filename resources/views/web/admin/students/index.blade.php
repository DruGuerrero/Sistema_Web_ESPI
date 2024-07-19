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
            <x-search-input-preline placeholder="Escribe un nombre" value="{{ request()->input('search') }}" name="search" />
        </div>
        <div class="form-group mr-2">
            <x-select-filter-preline 
                :options="['Matriculado', 'No matriculado']" 
                placeholder="Mostrar todos" 
                name="filter" 
                selected="{{ request()->input('filter') }}" 
            />
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