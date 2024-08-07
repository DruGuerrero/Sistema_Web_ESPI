@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Inicio</h1>
@stop

@section('content')
    <p>Bienvenido al panel administrativo del Instituto ESPI Bolivia.</p>

    <!-- Uso del componente Livewire DonutChart -->
    <livewire:donut-chart :series="[47, 23, 30]" :labels="['Tailwind CSS', 'Preline UI', 'Others']" id="chart1" />
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@stop