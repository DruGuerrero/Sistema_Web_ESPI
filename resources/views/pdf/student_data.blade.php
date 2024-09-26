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
            top: 20px;  /* Ajuste para la distancia desde el borde superior */
            left: 20px; /* Ajuste para la distancia desde el borde izquierdo */
            z-index: 10; /* Asegura que el logo esté siempre visible encima de otros elementos */
        }
        /* Contenedor principal */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0 50px;
            position: relative;
            z-index: 1;
        }
        /* Separador debajo del header */
        .separator {
            width: calc(100% - 100px); /* Asegura que el separador ocupe la mayor parte del ancho */
            height: 4px; /* Altura del separador */
            background-color: #004400; /* Color verde oscuro para el separador */
            margin: 0 auto 50px auto; /* Centra el separador y añade margen inferior */
        }
        /* Estilos del título centrado */
        .title-container {
            text-align: center;
            margin-top: 100px; /* Espacio superior para compensar el espacio ocupado por el logo */
            margin-left: 50px; /* Espacio lateral izquierdo */
            margin-right: 50px; /* Espacio lateral derecho */
        }
        .title-container h1 {
            font-size: 32px; /* Tamaño del título */
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
            position: relative;
            z-index: 2;
        }
        .info-group {
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        .info-group p {
            margin: 0;
            margin-bottom: 8px;
            font-size: 16px;
        }
        /* Estilos de la tabla que contiene la imagen y los datos */
        .info-table {
            width: 100%;
            margin-top: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 10px;
        }
        .student-photo {
            width: 150px; /* Tamaño de la imagen */
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
            opacity: 0.1; /* Ajusta la transparencia */
            width: 500px; /* Tamaño de la marca de agua */
            z-index: 0; /* Coloca detrás del contenido */
        }
    </style>
</head>
<body>
    <!-- Marca de agua -->
    <img src="{{ public_path('vendor/adminlte/dist/img/espi_logo.png') }}" alt="Marca de Agua" class="watermark">

    <!-- Logo en la esquina superior izquierda -->
    <div class="logo-container">
        <img src="{{ public_path('vendor/adminlte/dist/img/espi_text_logo.png') }}" alt="Logo" style="width: 200px;"> <!-- Aumenté el tamaño del logo -->
    </div>

    <!-- Título centrado -->
    <div class="title-container">
        <h1>Reporte del Estudiante</h1>
    </div>

    <!-- Separador debajo del encabezado -->
    <div class="separator"></div>

    <!-- Contenido principal -->
    <div class="container">
        <!-- Información del Estudiante -->
        <div class="info-group">
            <h3 class="section-title">Información del Estudiante</h3>

            <!-- Tabla con imagen y datos del estudiante -->
            <table class="info-table">
                <tr>
                    <!-- Foto del estudiante -->
                    <td>
                        <img src="{{ $photoPath }}" alt="Foto del Estudiante" class="student-photo">
                    </td>
                    <!-- Datos del estudiante alineados a la derecha de la imagen -->
                    <td>
                        <p><strong>Nombre completo:</strong> {{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</p>
                        <p><strong>Número de carnet:</strong> {{ $student->num_carnet }}</p>
                        <p><strong>E-mail:</strong> {{ $student->email }}</p>
                        <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_domicilio }}</p>
                        <p><strong>Número de celular:</strong> {{ $student->num_celular }}</p>
                        <p><strong>Usuario de Moodle:</strong> {{ $student->moodle_user ?? 'No asignado' }}</p>
                        <p><strong>Carrera:</strong> {{ $student->careers->first()->nombre ?? 'No asignada' }}</p>
                        <p><strong>Matriculado:</strong> {{ $student->matricula }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Información del Tutor -->
        <div class="info-group">
            <h3 class="section-title">Datos del Tutor</h3>
            <p><strong>Nombre completo:</strong> {{ $student->nombre_tutor }}</p>
            <p><strong>Número de celular:</strong> {{ $student->celular_tutor }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_tutor }}</p>
            <p><strong>Parentesco:</strong> {{ $student->parentesco }}</p>
        </div>
    </div>

    <!-- Pie de página con número de página y fecha -->
    <div class="footer">
        <span class="page-number"></span> | Generado el {{ date('d/m/Y') }}
    </div>
</body>
</html>
