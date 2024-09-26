@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="px-2.5">
        <h1>Usuarios</h1>
        <hr>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('moodleUserData'))
        <div class="modal fade" id="moodleUserModal" tabindex="-1" role="dialog" aria-labelledby="moodleUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moodleUserModalLabel">Cuenta Moodle Creada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Usuario Moodle:</strong> {{ session('moodleUserData')['moodle_user'] }}</p>
                        <p><strong>Contraseña Moodle:</strong> {{ session('moodleUserData')['moodle_pass'] }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario de búsqueda y filtrado -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline mb-3 px-2.5">
        <div class="form-group mr-2">
            <x-search-input-preline placeholder="Escribe un nombre o email" value="{{ request()->input('search') }}" name="search" />
        </div>
        <div class="form-group mr-2">
            <x-select-filter-preline 
                :options="$roles" 
                placeholder="Todos los roles" 
                name="role" 
                selected="{{ request()->input('role') }}" 
            />
        </div>        
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <div class="px-2.5">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Crear usuario</a>
    </div>
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

@section('js')
    @if (session('moodleUserData'))
        <script>
            $(document).ready(function() {
                $('#moodleUserModal').modal('show');
            });
        </script>
    @endif
@stop
