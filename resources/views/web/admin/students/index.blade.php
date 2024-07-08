@extends('adminlte::page')

@section('title', 'Lista de Estudiantes')

@section('content_header')
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Matricula</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $loop->iteration + $index }}</td>
                    <td>{{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</td>
                    <td>{{ $student->matricula }}</td>
                    <td>{{ $student->disabled ? 'Deshabilitado' : 'Habilitado' }}</td>
                    <td>
                        <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-info btn-sm">Ver detalles</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Agregar paginación -->
    {{ $students->links() }}
@stop