<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'sans-serif';
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .info-group {
            margin-bottom: 20px;
        }
        .info-group h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .info-group p {
            margin: 0;
            margin-bottom: 5px;
        }
        .course-list {
            margin-bottom: 20px;
            padding-left: 20px;
        }
        .course-list li {
            margin-bottom: 5px;
        }
        .divider {
            border-top: 1px solid #333;
            margin: 30px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de notas del Curso: {{ $course->nombre }}</h1>
        </div>

        <!-- Descripción del Curso -->
        <div class="info-group">
            <h3 class="section-title">Objetivo del Curso</h3>
            <p>{{ $course->descripcion }}</p>
        </div>
        <!-- Información de los estudiantes y sus calificaciones -->
        <div class="info-group">
            <h3 class="section-title">Estudiantes</h3>
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
</body>
</html>