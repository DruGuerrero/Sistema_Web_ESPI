@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @if ($user->disabled == 0)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@stop
