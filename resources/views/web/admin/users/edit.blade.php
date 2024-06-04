@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
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

@section('content')
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control">
            <small class="form-text text-muted">Para continual con la contraseña actual, dejar este espacio en blanco.</small>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="Administrativo" {{ $user->role == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                <option value="Jefe de carrera" {{ $user->role == 'Jefe de carrera' ? 'selected' : '' }}>Jefe de carrera</option>
                <option value="Docente" {{ $user->role == 'Docente' ? 'selected' : '' }}>Docente</option>
                <option value="Estudiante" {{ $user->role == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                <option value="Superusuario" {{ $user->role == 'Superusuario' ? 'selected' : '' }}>Superusuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
@stop
