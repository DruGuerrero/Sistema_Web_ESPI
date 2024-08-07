@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @vite(['resources/css/app.css','resources/js/app.js'])
    <h1>Inicio</h1>
@stop

@section('content')
    <p>Bienvenido al panel administrativo del Instituto ESPI Bolivia.</p>

    <!-- Contenedor del Gráfico Donut -->
    <div class="flex flex-col justify-center items-center w-full">
        <div id="hs-doughnut-chart" class="my-6"></div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        /* Asegurarse de que el contenedor del gráfico tenga suficiente espacio */
        #hs-doughnut-chart {
            width: 100%;
            max-width: 400px; /* Ajusta el tamaño máximo según sea necesario */
            margin: 0 auto;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const options = {
                chart: {
                    type: 'donut',
                    height: 320,  // Ajusta el tamaño del gráfico
                },
                series: [47, 23, 30], // Datos del gráfico
                labels: ['Tailwind CSS', 'Preline UI', 'Others'], // Etiquetas para los datos
                colors: ['#3b82f6', '#22d3ee', '#e5e7eb'], // Colores del gráfico
                dataLabels: {
                    enabled: false,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + '%';
                    },
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 2,
                        blur: 2,
                        opacity: 0.5
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                tooltip: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        colors: ['#000'] // Color negro para el texto del tooltip
                    },
                    y: {
                        formatter: function (val) {
                            return `${val}%`;
                        }
                    },
                    // Custom Tooltip CSS
                    custom: function({ series, seriesIndex, w }) {
                        return `
                            <div style="padding:8px; background-color:white; border-radius:5px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
                                <strong style="color:#000;">${w.globals.labels[seriesIndex]}</strong>: 
                                <span style="color:#000;">${series[seriesIndex]}%</span>
                            </div>
                        `;
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 280
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(document.querySelector("#hs-doughnut-chart"), options);
            chart.render();
        });
    </script>
@stop