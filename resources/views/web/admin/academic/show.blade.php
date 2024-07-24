@extends('adminlte::page')

@section('title', $career->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $career->nombre }}</h1>
        <a href="{{ route('admin.academic.create_year', ['career_id' => $career->id]) }}" class="btn btn-primary mb-3">Agregar año académico</a>
    </div>
    <hr>
@stop

@section('content')
    <p class="py-0.5">{{ strip_tags($career->descripcion) }}</p>
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
                    leftButtonLink="{{ $year->id }}" {{-- Pasar el ID del año --}}
                    leftButtonText="Eliminar"
                    rightButtonLink="{{ route('admin.academic.show_subcategory', ['id' => $year->id]) }}"
                    rightButtonText="Ver"
                />
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.index') }}" class="btn btn-primary">Volver</a>

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
                <div class="modal-body">
                    <p id="deleteItemMessage"></p>
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
        });
    </script>
@stop