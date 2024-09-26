@extends('adminlte::page')

@section('title', 'Deudas')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <h1 class="px-2.5 py-2.5">Deudas</h1>
    <hr>
@stop

@section('content')
    <!-- Formulario de búsqueda -->
    <form action="{{ route('admin.payments.show_debts') }}" method="GET" class="form-inline mb-3 px-2.5">
        <div class="input-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Escriba un nombre" value="{{ request()->input('search') }}">
            <span class="input-group-btn px-2">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </span>
        </div>
        <div class="form-group mr-2">
            <select name="product" class="form-control">
                <option value="">Seleccione un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ request()->input('product') == $product->id ? 'selected' : '' }}>{{ $product->nombre }}</option>
                @endforeach
            </select>
        </div>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </span>
    </form>

    @if ($debts->isEmpty())
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="mt-2 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                <span id="hs-soft-color-info-label" class="font-bold">Info!</span> No existen estudiantes con deudas pendientes.
            </div>
        </div>
    @else
        @php
            Log::info('Loading show_debts view');

            $headers = ['Nro', 'Estudiante', 'Producto', 'Monto Pendiente', 'Acciones'];
            $rows = $debts->map(function ($debt, $index) use ($debts) {
                return [
                    $debts->firstItem() + $index,
                    $debt->student->nombre . ' ' . $debt->student->apellido_paterno . ' ' . $debt->student->apellido_materno,
                    $debt->product->nombre,
                    $debt->monto_pendiente . ' Bs',
                    view('components.button-preline', [
                        'attributes' => new \Illuminate\View\ComponentAttributeBag([
                            'data-toggle' => 'modal',
                            'data-target' => '#payDebtModal',
                            'data-debt-id' => $debt->id,
                            'data-student-name' => $debt->student->nombre . ' ' . $debt->student->apellido_paterno . ' ' . $debt->student->apellido_materno,
                            'data-product-name' => $debt->product->nombre,
                            'data-monto-pendiente' => $debt->monto_pendiente,
                        ]),
                        'slot' => 'Pagar deuda'
                    ])->render()
                ];
            })->toArray();
        @endphp

        <x-table :headers="$headers" :rows="$rows" />

        <!-- Agregar paginación -->
        <x-pagination :paginator="$debts" />
    @endif
    <div class="px-2.5">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary mb-3">Volver</a>
    </div>

    <!-- Modal para pagar deuda -->
    <div class="modal fade" id="payDebtModal" tabindex="-1" role="dialog" aria-labelledby="payDebtModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payDebtModalLabel">Pagar Deuda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="payDebtForm" method="POST" action="{{ route('admin.debts.pay') }}">
                        @csrf
                        <input type="hidden" id="debt_id" name="debt_id">
                        <div class="form-group">
                            <label for="student_name">Estudiante</label>
                            <input type="text" class="form-control" id="student_name" name="student_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="product_name">Producto</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" readonly>
                        </div>
                        <div class="form-group">
                            <label for="monto_pendiente">Monto Pendiente</label>
                            <input type="text" class="form-control" id="monto_pendiente" name="monto_pendiente" readonly>
                        </div>
                        <div class="form-group">
                            <label for="monto_pagado">Monto a Pagar</label>
                            <x-price-input
                                id="monto_pagado"
                                name="monto_pagado"
                                placeholder="0.00"
                                currencySymbol="Bs"
                                currency="BOB"
                            />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar Pago</button>
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
        $('#payDebtModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var debtId = button.data('debt-id');
            var studentName = button.data('student-name');
            var productName = button.data('product-name');
            var montoPendiente = button.data('monto-pendiente');

            var modal = $(this);
            modal.find('#debt_id').val(debtId);
            modal.find('#student_name').val(studentName);
            modal.find('#product_name').val(productName);
            modal.find('#monto_pendiente').val(montoPendiente);
        });
    });
</script>
@stop