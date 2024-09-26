@extends('adminlte::page')

@section('title', 'Operar Pago')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="d-flex justify-content-between px-2.5">
        <h1>Operar Pago</h1>
    </div>
    <hr>
@stop

@section('content')
    <!-- Formulario de búsqueda -->
    <form action="{{ route('admin.payments.operate_payment') }}" method="GET" class="form-inline mb-3 px-2.5">
        <div class="input-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Escriba un nombre" value="{{ request()->input('search') }}">
            <span class="input-group-btn px-2">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </span>
        </div>
        <div class="form-group mr-2">
            <x-select-filter-preline 
                :options="$careers->pluck('nombre', 'id')" 
                placeholder="Seleccione una carrera" 
                name="career" 
                selected="{{ request()->input('career') }}" 
            />
        </div>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </span>
    </form>

    @if ($enrollments->isEmpty())
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="mt-2 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                <span id="hs-soft-color-info-label" class="font-bold">Info!</span> No existen estudiantes registrados.
            </div>
        </div>
    @else
        @php
            Log::info('Loading operate_payment view');

            $headers = ['Nro', 'Estudiante', 'Carrera', 'Acciones'];
            $rows = $enrollments->map(function ($enrollment, $index) use ($enrollments) {
                return [
                    $enrollments->firstItem() + $index,
                    $enrollment->student->nombre . ' ' . $enrollment->student->apellido_paterno . ' ' . $enrollment->student->apellido_materno,
                    $enrollment->career->nombre,
                    view('components.button-preline', [
                        'attributes' => new \Illuminate\View\ComponentAttributeBag([
                            'data-toggle' => 'modal',
                            'data-target' => '#registerPaymentModal',
                            'data-student-id' => $enrollment->student->id,
                            'data-student-name' => $enrollment->student->nombre . ' ' . $enrollment->student->apellido_paterno . ' ' . $enrollment->student->apellido_materno
                        ]),
                        'slot' => 'Registrar pago'
                    ])->render()
                ];
            })->toArray();
        @endphp

        <x-table :headers="$headers" :rows="$rows" />

        <!-- Agregar paginación -->
        <x-pagination :paginator="$enrollments" />
    @endif
    <div class="px-2.5">
        <a href="{{ route('admin.payments.show_payments') }}" class="btn btn-primary mb-3">Volver</a>
    </div>

    <!-- Modal para registrar pago -->
    <div class="modal fade" id="registerPaymentModal" tabindex="-1" role="dialog" aria-labelledby="registerPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerPaymentModalLabel">Registrar Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerPaymentForm" method="POST" action="{{ route('admin.payments.store') }}">
                        @csrf
                        <input type="hidden" id="student_id" name="student_id">
                        <div class="form-group">
                            <label for="student_name">Estudiante</label>
                            <input type="text" class="form-control" id="student_name" name="student_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="product_id">Seleccionar servicio o bien</label>
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">Seleccione un producto</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->precio }}">{{ $product->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Monto Pagado</label>
                            <x-price-input
                                id="hs-input-price"
                                name="price"
                                placeholder="0.00"
                                currencySymbol="Bs"
                                currency="BOB"
                            />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#registerPaymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var studentId = button.data('student-id');
            var studentName = button.data('student-name');

            var modal = $(this);
            modal.find('#student_id').val(studentId);
            modal.find('#student_name').val(studentName);
        });

        $('select[name="product_id"]').on('change', function() {
            var price = $(this).find(':selected').data('price');
            $('#hs-input-price').val(price);
        });
    });
</script>
@stop