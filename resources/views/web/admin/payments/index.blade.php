@extends('adminlte::page')

@section('title', 'Control de pagos')

@section('content_header')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="px-2.5 py-2.5">
        <h1>Control de pagos</h1>
    </div>
    <hr>
@stop

@section('content')
    <div class="flex flex-wrap justify-center items-center">
        <a href="{{ route('admin.payments.show_debts') }}">
            <div class="p-2">
                <x-advanced-button image="{{ asset('vendor/adminlte/dist/img/default_student.png') }}" text="Deudas"/>
            </div>
        </a>
        <a href="{{ route('admin.payments.show_products') }}">
            <div class="p-2">
                <x-advanced-button image="{{ asset('vendor/adminlte/dist/img/list.png') }}" text="Servicos y bienes académicos" />
            </div>
        </a>
        <a href="{{ route('admin.payments.show_payments') }}">
            <div class="p-2">
                <x-advanced-button image="{{ asset('vendor/adminlte/dist/img/money.png') }}" text="Pagos realizados" />
            </div>
        </a>
    </div>
@stop