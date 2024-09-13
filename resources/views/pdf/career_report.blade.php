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
        .course-list {
            margin-bottom: 16px;
            padding-left: 16px;
        }
        .course-list li {
            margin-bottom: 4px;
        }
        .divider {
            border-top: 1px solid #333;
            margin: 30px 0;
            margin-right: 100px;
        }
        /* Ajustes de la tabla */
        .table-container {
            padding-right: 100px; /* Hace que la tabla ocupe el ancho restante */
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            table-layout: fixed; /* Hace que las columnas tengan un ancho fijo */
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-size: 14px;
        }
        .table td {
            font-size: 12px;
            word-wrap: break-word; /* Asegura que el contenido largo se ajuste al tamaño de la celda */
        }
        /* Ancho específico para las columnas */
        .table th:nth-child(1), .table td:nth-child(1) {
            width: 25%; /* Ancho para el nombre completo */
        }
        .table th:nth-child(2), .table td:nth-child(2) {
            width: 15%; /* Ancho para el número de carnet */
        }
        .table th:nth-child(3), .table td:nth-child(3) {
            width: 30%; /* Ancho para el email */
        }
        .table th:nth-child(4), .table td:nth-child(4) {
            width: 15%; /* Ancho para la ciudad de domicilio */
        }
        .table th:nth-child(5), .table td:nth-child(5) {
            width: 15%; /* Ancho para el número de celular */
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
        <h1>Reporte de la Carrera: {{ $career->nombre }}</h1>
    </div>

    <!-- Separador debajo del encabezado -->
    <div class="separator"></div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Información de los años académicos y cursos -->
        @foreach($career->years as $year)
            <div class="info-group">
                <h3 class="section-title">{{ $year->nombre }}</h3>
                <p>{{ $year->descripcion }}</p>
                <div class="course-list">
                    <h4 class="font-semibold">Cursos:</h4>
                    <ul>
                        @foreach($year->courses as $course)
                            <li>
                                <strong>{{ $course->nombre }}</strong>
                                <br>
                                <span>Profesor: {{ $course->docente->name ?? 'No asignado' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="divider"></div>
        @endforeach

        <!-- Información de los estudiantes -->
        <div class="info-group">
            <h3 class="section-title">Estudiantes</h3>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Número de Carnet</th>
                            <th>Email</th>
                            <th>Ciudad de Domicilio</th>
                            <th>Número de Celular</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</td>
                                <td>{{ $student->num_carnet }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->ciudad_domicilio }}</td>
                                <td>{{ $student->num_celular }}</td>
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
