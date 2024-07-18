<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicManagementController extends Controller
{
    public function index()
    {
        // Obtener las carreras de la base de datos, por ahora usamos datos ficticios.
        $careers = [
            ['name' => 'Enfermería', 'students' => 999, 'description' => 'Esta es la descripción de la carrera o curso. Descripción. Descripción.'],
            ['name' => 'Enfermería', 'students' => 999, 'description' => 'Esta es la descripción de la carrera o curso. Descripción. Descripción.'],
            ['name' => 'Enfermería', 'students' => 999, 'description' => 'Esta es la descripción de la carrera o curso. Descripción. Descripción.'],
            ['name' => 'Enfermería', 'students' => 999, 'description' => 'Esta es la descripción de la carrera o curso. Descripción. Descripción.'],
            ['name' => 'Enfermería', 'students' => 999, 'description' => 'Esta es la descripción de la carrera o curso. Descripción. Descripción.'],
            // Agregar más carreras según sea necesario.
        ];

        return view('web.admin.academic.index', compact('careers'));
    }

    // Aquí puedes agregar más métodos como create, store, edit, update, destroy
}
