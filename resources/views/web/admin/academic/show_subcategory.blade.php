@extends('adminlte::page')

@section('title', $year->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $year->nombre }}</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#editYearModal">Editar año académico</button>
    </div>
    <hr>
@stop

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <p>{{ strip_tags($year->descripcion) }}</p>
        </div>
        <div>
            <span class="px-3">{{ $year->cant_estudiantes }} estudiantes</span>
            <a href="{{ route('admin.academic.create_course', ['subcategory_id' => $year->id]) }}" class="btn btn-primary mb-3">Agregar curso</a>
            
            <!-- Mostrar el botón solo si el año es "Primer Año" -->
            @if ($year->nombre === 'Primer año')
                <!-- Botón para matricular estudiantes al Segundo Año -->
                <form id="enrollSecondYearForm" action="{{ route('admin.academic.enroll_second_year', ['id' => $year->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Matricular al Segundo Año</button>
                </form>
            @endif
        </div>
    </div>

    <div class="flex justify-center">
        <div class="grid gap-4 grid-cols-[repeat(auto-fit,minmax(300px,1fr))] sm:grid-cols-2 lg:grid-cols-3 w-full">
            @foreach($courses as $course)
                <x-advanced-card
                    title="{{ $course['name'] }}"
                    image="{{ $course['image'] }}"
                    content="{{ $course['description'] }}"
                    :contentBlocks="[['name' => 'Docente', 'professor' => $course['professor']]]"
                    leftButtonLink="{{ $course['id'] }}"
                    leftButtonText="Eliminar"
                    rightButtonLink="{{ route('admin.academic.show_course', ['id' => $course['id']]) }}"
                    rightButtonText="Ver"
                />
            @endforeach
        </div>
    </div>     

    <a href="{{ route('admin.academic.show', ['id' => $year->id_career]) }}" class="btn btn-primary">Volver</a>

    <!-- Modal para editar año académico -->
    <div class="modal fade" id="editYearModal" tabindex="-1" role="dialog" aria-labelledby="editYearModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editYearModalLabel">Editar Año Académico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editYearForm" action="{{ route('admin.academic.update_subcategory', ['id' => $year->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $year->nombre) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción:</label>
                            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $year->descripcion) }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer flex justify-between mt-6">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="saveYearChanges">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de matriculación -->
    <div class="modal fade" id="enrollSuccessModal" tabindex="-1" role="dialog" aria-labelledby="enrollSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollSuccessModalLabel">Matriculación exitosa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Todos los estudiantes han sido matriculados correctamente al Segundo Año.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="font-bold" id="deleteItemMessage"></p>
                    <p>Esta acción no podrá deshacerse.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @if (session('enroll_success'))  <!-- Solo detectar mensaje de éxito para la matriculación al Segundo Año -->
        <script>
            $(document).ready(function() {
                $('#enrollSuccessModal').modal('show');  <!-- Mostrar el modal al cargar la página -->
            });
        </script>
    @endif

    <script>
        function handleDelete(event, itemId, itemName) {
            event.preventDefault();
            $('#deleteItemMessage').text('¿Está seguro/a que quiere eliminar "' + itemName + '"?');
            $('#confirmDeleteButton').data('item-id', itemId);
            $('#deleteItemModal').modal('show');
        }

        $(document).ready(function() {
            // Confirmar eliminación del elemento
            $('#confirmDeleteButton').on('click', function() {
                var itemId = $(this).data('item-id');
                $.ajax({
                    url: '{{ route("admin.academic.items.destroy", ":id") }}'.replace(':id', itemId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#deleteItemModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('Error al eliminar el elemento: ' + xhr.responseText);
                    }
                });
            });
            // Guardar cambios de año académico
            $('#saveYearChanges').on('click', function() {
                $('#editYearForm').submit();
            });
        });
    </script>
@stop
