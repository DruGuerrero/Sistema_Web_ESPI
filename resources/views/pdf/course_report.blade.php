<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Arial, sans-serif';
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        /* Logo en la esquina superior izquierda */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        /* Contenedor principal */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0 50px; /* Añadido padding de 50px en los lados izquierdo y derecho */
            position: relative;
            z-index: 1;
        }
        /* Separador debajo del header */
        .separator {
            width: calc(100% - 100px);
            height: 4px;
            background-color: #004400;
            margin: 0 auto 50px auto;
        }
        /* Estilos del título centrado */
        .title-container {
            text-align: center;
            margin-top: 100px;
            margin-left: 50px;
            margin-right: 50px;
        }
        .title-container h1 {
            font-size: 32px;
            font-weight: bold;
        }
        /* Estilos de las secciones */
        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #006600;
            border-bottom: 2px solid #000000;
            padding-bottom: 5px;
            margin-right: 100px;
        }
        .info-group {
            margin-bottom: 30px;
        }
        .info-group p {
            margin: 0;
            margin-bottom: 8px;
            font-size: 16px;
        }
        /* Ajustes de la tabla */
        .table-container {
            padding-right: 100px; /* Hace que la tabla ocupe el ancho restante */
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed; /* Hace que las columnas tengan un ancho fijo */
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #dbd9d9;
            font-size: 14px;
        }
        .table td {
            background-color: #f2f2f2;
            font-size: 12px;
            word-wrap: break-word; /* Asegura que el contenido largo se ajuste al tamaño de la celda */
        }
        /* Pie de página */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #666;
            z-index: 2;
        }
        .page-number:before {
            content: "Página " counter(page);
        }
        /* Marca de agua */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            width: 500px;
            z-index: 0;
        }
    </style>
</head>
<body>
    <!-- Marca de agua -->
    <img src="{{ public_path('vendor/adminlte/dist/img/espi_logo.png') }}" alt="Marca de Agua" class="watermark">

    <!-- Logo en la esquina superior izquierda -->
    <div class="logo-container">
        <img src="{{ public_path('vendor/adminlte/dist/img/espi_text_logo.png') }}" alt="Logo" style="width: 200px;">
    </div>

    <!-- Título centrado -->
    <div class="title-container">
        <h1>Reporte de notas del Curso: {{ $course->nombre }}</h1>
    </div>

    <!-- Separador debajo del encabezado -->
    <div class="separator"></div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Descripción del Curso -->
        <div class="info-group">
            <h3 class="section-title">Objetivo del Curso</h3>
            <p>{{ $course->descripcion }}</p>
        </div>

        <!-- Información de los estudiantes y sus calificaciones -->
        <div class="info-group">
            <h3 class="section-title">Estudiantes</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            @foreach(array_keys($tasks) as $taskName)
                                <th>{{ $taskName }}</th>
                            @endforeach
                            <th>Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student['fullname'] }}</td>
                                @foreach($tasks as $taskName => $value)
                                    <td>{{ $student['grades'][$taskName] ?? 'N/A' }}</td>
                                @endforeach
                                <td>{{ $student['average_grade'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pie de página con número de página y fecha -->
    <div class="footer">
        <span class="page-number"></span> | Generado el {{ date('d/m/Y') }}
    </div>
</body>
</html>
