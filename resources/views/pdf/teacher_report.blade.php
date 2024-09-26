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
        /* Contenedor principal */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0 50px; /* Ajusté el padding superior e inferior a 0 */
            position: relative;
            z-index: 1;
        }
        /* Estilos del encabezado */
        .header {
            color: #004400; /* Texto en un tono verde oscuro para contraste */
            text-align: center;
            padding: 30px 0;
            margin-bottom: 0px;
            margin-top: 100px; /* Márgenes superiores para que el fondo no toque los bordes */
            margin-left: 0px; /* Márgenes laterales para que el fondo no toque los bordes */
            margin-right: 0px;
            position: relative;
            z-index: 2;
        }
        .header h1 {
            font-size: 32px;
            margin: 0;
            font-weight: bold;
        }
        /* Separador debajo del header */
        .separator {
            width: calc(100% - 100px); /* Ancho total menos 50px de cada lado */
            height: 4px;
            background-color: #004400; /* Color de la línea de separación */
            margin: 0 auto 50px auto; /* Centrar el separador horizontalmente */
            margin-top: 50px; /* Márgenes superiores para que el fondo no toque los bordes */
            position: relative;
            z-index: 2;
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
            position: relative;
            z-index: 2;
        }
        .info-group {
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }
        .info-group h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #0b700b;
        }
        .info-group p {
            margin: 0;
            margin-bottom: 8px;
            font-size: 16px;
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
        /* Opcional: Logo */
        .logo {
            position: absolute;
            top: 20px;
            left: 50px;
            width: 200px;
            margin-top: 40px;
        }
        /* Marca de agua */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1; /* Ajusta la transparencia */
            width: 500px; /* Tamaño de la marca de agua */
            z-index: 0; /* Coloca detrás del contenido */
        }
    </style>
</head>
<body>
    <!-- Logo de la institución (opcional) -->
    <img src="{{ public_path('vendor\adminlte\dist\img\espi_text_logo.png') }}" alt="Logo" class="logo">

    <!-- Marca de agua -->
    <img src="{{ public_path('vendor/adminlte/dist/img/espi_logo.png') }}" alt="Marca de Agua" class="watermark">

    <!-- Encabezado -->
    <div class="header">
        <h1>Reporte del Docente</h1>
    </div>

    <!-- Separador debajo del encabezado -->
    <div class="separator"></div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Información del Docente -->
        <div class="info-group">
            <h3 class="section-title">Información del Docente</h3>
            <p><strong>Nombre completo:</strong> {{ $teacher->name }}</p>
            <p><strong>Email:</strong> {{ $teacher->email }}</p>
            <!-- Puedes agregar más información del docente si está disponible -->
        </div>

        <!-- Información del Curso -->
        <div class="info-group">
            <h3 class="section-title">Curso Asignado</h3>
            <p><strong>Nombre del Curso:</strong> {{ $course->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $course->descripcion }}</p>
        </div>
    </div>

    <!-- Pie de página con número de página y fecha -->
    <div class="footer">
        <span class="page-number"></span> | Generado el {{ date('d/m/Y') }}
    </div>
</body>
</html>
