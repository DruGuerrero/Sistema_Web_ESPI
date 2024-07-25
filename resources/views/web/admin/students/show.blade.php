@extends('adminlte::page')

@section('title', 'Detalles del Estudiante')

@section('content_header')
    <h1>{{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <img src="{{ $photoUrl }}" alt="Foto tipo carnet" class="img-thumbnail">
            <p><strong>Número de carnet:</strong> {{ $student->num_carnet }}</p>
            <p><strong>E-mail:</strong> {{ $student->email }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_domicilio }}</p>
            <p><strong>Número de celular:</strong> {{ $student->num_celular }}</p>
            <p><strong>Usuario de moodle:</strong> {{ $student->moodle_user ?? 'No asignado' }}</p>
            <p><strong>Carrera:</strong> {{ $student->careers->first()->nombre ?? 'No asignada' }}</p>
            <p><strong>Matriculado:</strong> {{ $student->matricula }}</p>

            <hr>

            <h5>Datos del tutor</h5>
            <p><strong>Nombre completo:</strong> {{ $student->nombre_tutor }}</p>
            <p><strong>Número de celular:</strong> {{ $student->celular_tutor }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_tutor }}</p>
            <p><strong>Parentesco:</strong> {{ $student->parentesco }}</p>

            <hr>

            <h5>Archivos del estudiante</h5>
            @if($files->isEmpty())
                <p>No hay archivos guardados para este estudiante.</p>
            @else
                <ul>
                    @foreach($files as $file)
                        <li>
                            <a href="{{ route('admin.students.download', $file->id) }}" class="btn btn-primary">
                                Descargar {{ $file->type }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Volver</a>
            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary">Editar</a>

            @if($student->matricula === 'NO')
                <button class="btn btn-success" id="matricular-btn">Matricular</button>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="moodleModal" tabindex="-1" role="dialog" aria-labelledby="moodleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moodleModalLabel">Datos de Moodle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Usuario:</strong> <span id="moodle-user"></span></p>
                    <p><strong>Contraseña:</strong> <span id="moodle-pass"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-body img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
@stop

@section('js')
    <script>
        document.getElementById('matricular-btn').addEventListener('click', function() {
            fetch('{{ route('admin.students.matriculate', $student->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('moodle-user').textContent = data.moodle_user;
                document.getElementById('moodle-pass').textContent = data.moodle_pass;
                $('#moodleModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@stop
