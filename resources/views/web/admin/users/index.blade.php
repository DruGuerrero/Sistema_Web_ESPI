@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Usuarios</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulario de búsqueda y filtrado -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Buscar usuario" value="{{ request()->input('search') }}">
        </div>
        <div class="form-group mr-2">
            <select name="role" class="form-control">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request()->input('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Crear usuario</a>

    @php
        $headers = ['N°', 'Nombre', 'Email', 'Rol', 'Acciones'];
        $rows = $users->map(function ($user, $index) use ($users) {
            return [
                $users->firstItem() + $index,
                $user->name,
                $user->email,
                $user->role,
                view('components.button-preline', [
                    'attributes' => new \Illuminate\View\ComponentAttributeBag(['onclick' => "window.location='".route('admin.users.edit', $user->id)."'"]),
                    'slot' => 'Editar'
                ])->render()
            ];
        })->toArray();
    @endphp

    <x-table :headers="$headers" :rows="$rows" />

    <!-- Paginación -->
    <x-pagination :paginator="$users" />
@stop
