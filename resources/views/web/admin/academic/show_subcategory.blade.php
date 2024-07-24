@extends('adminlte::page')

@section('title', $year->nombre)

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <div class="d-flex justify-content-between">
        <h1>{{ $year->nombre }}</h1>
        <a href="#" class="btn btn-primary mb-3">Editar año académico</a>
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
        </div>
    </div>
    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-4 mb-4">
                <x-advanced-card
                    title="{{ $course['name'] }}"
                    image="{{ $course['image'] }}"
                    content="{{ $course['description'] }}"
                    :contentBlocks="[['name' => 'Docente', 'professor' => $course['professor']]]"
                    leftButtonLink="{{ $course['id'] }}" {{-- Pasar el ID del elemento --}}
                    leftButtonText="Eliminar"
                    rightButtonLink="#"
                    rightButtonText="Ver"
                />
                {{-- Log para verificar los datos pasados al componente --}}
                @php
                    Log::info('Course Data Passed to Component:', [
                        'name' => $course['name'],
                        'image' => $course['image'],
                        'description' => $course['description'],
                        'professor' => $course['professor']
                    ]);
                @endphp
            </div>
        @endforeach
    </div>
    <a href="{{ route('admin.academic.show', ['id' => $year->id_career]) }}" class="btn btn-primary">Volver</a>

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
    @if (session('moodleUserData'))
        <script>
            $(document).ready(function() {
                $('#moodleUserModal').modal('show');
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
        });
    </script>    
@stop