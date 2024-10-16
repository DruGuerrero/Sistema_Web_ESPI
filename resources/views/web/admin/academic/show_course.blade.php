@extends('adminlte::page')

@section('title', $course->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between px-2.5">
        <h1>{{ $course->nombre }}</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#editCourseModal">Editar Curso</button>
    </div>
    <hr>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between px-2.5">
        <p>{{ $course->descripcion }}</p>       
        <form id="refreshCacheForm" action="{{ route('admin.academic.refresh_cache', ['id' => $course->id]) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">Actualizar</button>
        </form>
    </div>
    <div class="d-flex justify-end px-2.5 py-2">
        <a href="{{ route('admin.academic.generate_course_report', ['id' => $course->id]) }}" class="btn btn-info mb-3">
            Descargar Reporte del Curso
        </a>
        <a href="{{ route('admin.academic.generate_teacher_report_by_course', ['id' => $course->id]) }}" class="btn btn-info mb-3 ml-2">
            Descargar Reporte del Docente
        </a>
    </div>    
    @php
        $headers = ['N°', 'Nombre', 'Promedio'];
        $rows = $students->map(function ($student, $index) {
            return [
                $index + 1,
                $student['fullname'],
                $student['average_grade'],
            ];
        })->toArray();
    @endphp

    <x-table :headers="$headers" :rows="$rows" />
    <a href="javascript:history.back()" class="btn btn-primary">Volver</a>

    <!-- Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel">Editar Curso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm" action="{{ route('admin.academic.update_course', ['id' => $course->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $course->nombre) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción:</label>
                            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $course->descripcion) }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="saveCourseChanges">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('saveCourseChanges').addEventListener('click', function () {
            document.getElementById('editCourseForm').submit();
        });
    </script>
@stop