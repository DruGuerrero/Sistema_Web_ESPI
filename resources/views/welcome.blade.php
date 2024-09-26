<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Instituto ESPI Bolivia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Estilos -->
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Nunito', sans-serif;
        }

        /* Contenedor principal */
        .container {
            min-height: 100%;
            position: relative;
        }

        /* Encabezado con imagen de fondo */
        .header {
            position: relative;
            background-image: url('{{ asset('vendor/adminlte/dist/img/espi_portada.png') }}');
            background-size: cover;
            background-position: center;
            height: 70vh; /* Ajusta la altura según tus preferencias */
        }

        /* Sombra sobre la imagen para mejorar legibilidad */
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Oscurece la imagen un poco */
            z-index: 1; /* Asegura que esté detrás del eslogan y los botones */
        }

        /* Eslogan */
        .slogan {
            position: absolute;
            bottom: 20px; /* Distancia desde el borde inferior */
            left: 50%; /* Centrado horizontalmente */
            transform: translateX(-50%); /* Centrado horizontalmente */
            color: #fff;
            font-size: 3rem;
            text-align: center;
            z-index: 2; /* Coloca el eslogan encima del pseudo-elemento */
            margin: 0; /* Elimina márgenes predeterminados */
        }

        /* Enlaces de inicio de sesión */
        .top-right {
            position: absolute;
            right: 20px;
            top: 20px;
            z-index: 2; /* Coloca los botones encima del pseudo-elemento */
        }

        .top-right a {
            display: inline-block;
            color: #fff;
            background-color: #1f2937; /* Color de fondo actualizado */
            font-weight: 600;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .top-right a:hover {
            background-color: #374151; /* Color de fondo al pasar el ratón (un tono más claro) */
        }

        /* Pie de página */
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px; /* Altura del pie de página */
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer p {
            margin: 0;
            color: #6c757d;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .slogan {
                font-size: 2rem;
                padding: 0 20px;
            }

            .top-right a {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Enlaces de inicio de sesión -->
        @if (Route::has('login'))
            <div class="top-right">
                @auth
                    <a href="{{ url('admin/Panel-Administrativo') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                @endauth
            </div>
        @endif

        <!-- Encabezado con imagen de fondo -->
        <div class="header">
            <!-- Eslogan -->
            <h1 class="slogan">Construyendo tu futuro</h1>
        </div>

        <!-- Contenido adicional si lo deseas -->
        <!--
        <div class="content">
            Aquí puedes agregar más contenido sobre el instituto, noticias, etc.
        </div>
        -->

        <!-- Pie de página -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Instituto ESPI Bolivia. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
