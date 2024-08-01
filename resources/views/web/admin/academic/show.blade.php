@extends('adminlte::page')

@section('title', $career->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $career->nombre }}</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#editCareerModal">Editar carrera</button>
    </div>
    <hr>
@stop

@section('content')
    <div class="d-flex justify-content-between">
        <p class="py-0.5">{{ strip_tags($career->descripcion) }}</p>
        <a href="{{ route('admin.academic.create_year', ['career_id' => $career->id]) }}" class="btn btn-primary mb-3">Agregar año académico</a>
    </div>
    <div class="flex flex-wrap justify-center items-center">
        <div class="row">
            @foreach($career->years as $year)
                <div class="col-md-6 mb-4">
                    <x-advanced-card
                        title="{{ $year->nombre }}"
                        content="{{ strip_tags($year->descripcion) }}"
                        :contentBlocks="$year->courses->map(function($course) {
                            return [
                                'name' => $course->nombre,
                                'professor' => $course->docente->name,
                            ];
                        })->toArray()"
                        leftButtonLink="{{ $year->id }}"
                        leftButtonText="Eliminar"
                        rightButtonLink="{{ route('admin.academic.show_subcategory', ['id' => $year->id]) }}"
                        rightButtonText="Ver"
                    />
                </div>
            @endforeach
        </div>
    </div>
    <a href="{{ route('admin.academic.index') }}" class="btn btn-primary">Volver</a>

    <!-- Modal para editar carrera -->
    <div class="modal fade" id="editCareerModal" tabindex="-1" role="dialog" aria-labelledby="editCareerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCareerModalLabel">Editar Carrera</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCareerForm" action="{{ route('admin.academic.update_category', ['id' => $career->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $career->nombre) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción:</label>
                            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $career->descripcion) }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveCareerChanges">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de confirmación -->
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
                    url: '{{ route("admin.academic.years.destroy", ":id") }}'.replace(':id', itemId),
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
            // Guardar cambios de carrera
            $('#saveCareerChanges').on('click', function() {
                $('#editCareerForm').submit();
            });
        });
    </script>
@stop