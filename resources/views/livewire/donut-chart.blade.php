<div class="flex flex-col justify-center items-center w-full">
    <div id="donut-chart-{{ $id }}" class="my-6"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                chart: {
                    type: 'donut',
                    height: 320,
                },
                series: @json($series), // Datos del gráfico
                labels: @json($labels), // Etiquetas para los datos
                colors: ['#3b82f6', '#22d3ee', '#e5e7eb'], // Colores del gráfico
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                tooltip: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        colors: ['#000']
                    },
                    y: {
                        formatter: function (val) {
                            return `${val}%`;
                        }
                    },
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

            const chart = new ApexCharts(document.querySelector("#donut-chart-{{ $id }}"), options);
            chart.render();
        });
    </script>
</div>