<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Inscripción</title>
    <style>
        /* Agrega aquí tus estilos CSS si es necesario */
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
<body>
    <h1>Contrato de Inscripción y Nueva Admisión</h1>
    <p>En la Ciudad de Montero a los {{ now()->format('d') }} días del mes de {{ now()->format('F') }} del año {{ now()->format('Y') }}, entre el INSTITUTO TÉCNICO INTEGRAL “ESPI-BOLIVIA” con Resolución Administrativa 201/07 y Resolución Ministerial Vigente 0617/19 en su representación como representante legal el Señor PhD. Dr. Arturo Sanjinéz Menacho con C.I. 1661618 Tja. Con domicilio constituido en la Calle 24 de Septiembre 280 de la ciudad de Montero por una parte y por la otra el estudiante inscrito a la gestión {{ now()->format('Y') }} Sr(a) {{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }} con C.I. {{ $student->num_carnet }} cuyos datos se consignan en el formulario de postulación respectivo, anexo a este contrato, se ha convenido el siguiente Contrato para establecer las responsabilidades respecto de la permanencia en ESPI-BOLIVIA, durante la gestión total de formación que corresponda.</p>

    <!-- Continúa el contenido del contrato... -->
    
    <p>Nombre Estudiante: {{ $student->nombre }} {{ $student->apellido_paterno }} {{ $student->apellido_materno }}</p>
    <p>Nombre Tutor o Garante/Apoderado: {{ $student->nombre_tutor }}</p>
    <p>C.I.: {{ $student->num_carnet }}</p>
    <p>C.I. Tutor: {{ $student->ciudad_tutor }}</p>
    
    <!-- Firmas -->
    <p>Firma Estudiante: __________________________</p>
    <p>Firma Tutor: __________________________</p>
</body>
</html>
