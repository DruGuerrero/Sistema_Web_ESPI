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
        .header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }
        .header h2 {
            font-size: 24px;
            margin: 0;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .info-group {
            margin-bottom: 20px;
        }
        .info-group p {
            margin: 0;
            margin-bottom: 5px;
        }
        .divider {
            border-top: 1px solid #333;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $photoPath }}" alt="Foto tipo carnet">
            <h2>Datos del Estudiante</h2>
        </div>
        <div class="info-group">
            <p><strong>Nombre completo:</strong> {{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</p>
            <p><strong>Número de carnet:</strong> {{ $student->num_carnet }}</p>
            <p><strong>E-mail:</strong> {{ $student->email }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_domicilio }}</p>
            <p><strong>Número de celular:</strong> {{ $student->num_celular }}</p>
            <p><strong>Usuario de Moodle:</strong> {{ $student->moodle_user ?? 'No asignado' }}</p>
            <p><strong>Carrera:</strong> {{ $student->careers->first()->nombre ?? 'No asignada' }}</p>
            <p><strong>Matriculado:</strong> {{ $student->matricula }}</p>
        </div>
        <div class="divider"></div>
        <div class="info-group">
            <h3 class="section-title">Datos del Tutor</h3>
            <p><strong>Nombre completo:</strong> {{ $student->nombre_tutor }}</p>
            <p><strong>Número de celular:</strong> {{ $student->celular_tutor }}</p>
            <p><strong>Ciudad de domicilio:</strong> {{ $student->ciudad_tutor }}</p>
            <p><strong>Parentesco:</strong> {{ $student->parentesco }}</p>
        </div>
    </div>
</body>
</html>