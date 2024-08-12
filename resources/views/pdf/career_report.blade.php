<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>

        body {
            font-family: 'sans-serif';
            color: #333;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 16px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 4px;
        }

        .info-group {
            margin-bottom: 16px;
        }

        .info-group h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .info-group p {
            margin: 0;
            margin-bottom: 4px;
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
            margin: 16px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-size: 14px;
        }

        .table td {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de la Carrera: {{ $career->nombre }}</h1>
        </div>

        <!-- Información de los años académicos y cursos -->
        @foreach($career->years as $year)
            <div class="info-group">
                <h3 class="section-title">{{ $year->nombre }}</h3>
                <p>{{ $year->descripcion }}</p>
                <div class="course-list">
                    <h4 class="font-semibold">Cursos:</h4>
                    <ul class="list-disc list-inside">
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
</body>
</html>