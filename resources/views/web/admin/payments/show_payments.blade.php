@extends('adminlte::page')

@section('title', 'Pagos Realizados')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="d-flex justify-content-between px-2.5">
        <h1>Pagos Realizados</h1>
        <a href="{{ route('admin.payments.operate_payment') }}" class="btn btn-primary mb-3">
            Operar pago
        </a>
    </div>
    <hr>
@stop

@section('content')
    <!-- Formulario de búsqueda -->
    <form action="{{ route('admin.payments.show_payments') }}" method="GET" class="form-inline mb-3 px-2.5">
        <div class="input-group mr-2">
            <input type="text" name="search" class="form-control" placeholder="Escriba un nombre" value="{{ request()->input('search') }}">
            <span class="input-group-btn px-2">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </span>
        </div>
        <div class="form-group mr-2">
            <select name="product" class="form-control">
                <option value="">Seleccione un servicio o bien</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ request()->input('product') == $product->id ? 'selected' : '' }}>{{ $product->nombre }}</option>
                @endforeach
            </select>
        </div>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </span>
    </form>

    @if($payments->isEmpty())
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="mt-2 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                <span id="hs-soft-color-info-label" class="font-bold">Info!</span> No existen pagos realizados.
            </div>
        </div>
    @else
        @php
            $headers = ['Nro', 'Estudiante', 'Servicio/Bien', 'Monto'];
            $rows = $payments->map(function ($payment, $index) use ($payments) {
                return [
                    $payments->firstItem() + $index,
                    $payment->student->nombre . ' ' . $payment->student->apellido_paterno . ' ' . $payment->student->apellido_materno,
                    $payment->product->nombre,
                    $payment->monto_pagado . ' Bs',
                ];
            })->toArray();
        @endphp

        <x-table :headers="$headers" :rows="$rows" />

        <!-- Agregar paginación -->
        <x-pagination :paginator="$payments" />
    @endif
    <div class="px-2.5">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary mb-3">Volver</a>
    </div>
@stop