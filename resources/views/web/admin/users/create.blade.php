@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
    <h1>Create User</h1>
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
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="Administrativo" {{ old('role') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                <option value="Jefe de carrera" {{ old('role') == 'Jefe de carrera' ? 'selected' : '' }}>Jefe de carrera</option>
                <option value="Docente" {{ old('role') == 'Docente' ? 'selected' : '' }}>Docente</option>
                <option value="Estudiante" {{ old('role') == 'Estudiante' ? 'selected' : '' }}>Estudiante</option>
                <option value="Superusuario" {{ old('role') == 'Superusuario' ? 'selected' : '' }}>Superusuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create</button>
    </form>
@stop
