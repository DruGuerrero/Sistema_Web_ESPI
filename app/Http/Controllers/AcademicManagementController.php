<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class AcademicManagementController extends Controller
{
    public function index()
    {
        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        // Intentar obtener de la caché primero
        $careers = Cache::remember('moodle_careers', 60, function () use ($apikey) {
            $careers = [];

            // Obtener categorías padre
            $categoriesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                . '&wstoken=' . urldecode($apikey)
                . '&criteria[0][key]=parent'
                . '&criteria[0][value]=0'
            );

            if ($categoriesResponse->failed()) {
                Log::error('Error obteniendo categorías de Moodle: ' . $categoriesResponse->body());
                return []; // Retornar un array vacío en caso de error
            }

            $categories = $categoriesResponse->json();

            foreach ($categories as $category) {
                if ($category['parent'] != 0) {
                    continue; // Asegurarse de que solo se procesan las categorías padre
                }
                
                $categoryId = $category['id'];
                $categoryName = $category['name'];
                $categoryDescription = strip_tags($category['description']); // Eliminar etiquetas HTML

                // Inicializar el conjunto de estudiantes únicos
                $uniqueStudents = collect();

                // Obtener categorías hijo
                $subCategoriesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                    . '&wstoken=' . urldecode($apikey)
                    . '&criteria[0][key]=parent'
                    . '&criteria[0][value]=' . $categoryId
                );

                if ($subCategoriesResponse->failed()) {
                    Log::error('Error obteniendo subcategorías de Moodle: ' . $subCategoriesResponse->body());
                    continue; // Saltar esta categoría padre en caso de error
                }

                $subCategories = $subCategoriesResponse->json();

                // Procesar cada subcategoría para contar los estudiantes
                foreach ($subCategories as $subCategory) {
                    // Obtener cursos por subcategoría
                    $coursesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field'
                        . '&wstoken=' . urldecode($apikey)
                        . '&field=category'
                        . '&value=' . $subCategory['id']
                    );

                    if ($coursesResponse->failed()) {
                        Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
                        continue; // Saltar esta subcategoría en caso de error
                    }

                    $courses = $coursesResponse->json()['courses'];

                    // Contar estudiantes matriculados en cada curso
                    foreach ($courses as $course) {
                        $courseId = $course['id'];
                        $enrolledUsersResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_enrol_get_enrolled_users'
                            . '&wstoken=' . urldecode($apikey)
                            . '&courseid=' . $courseId
                        );

                        if ($enrolledUsersResponse->failed()) {
                            Log::error('Error obteniendo estudiantes matriculados en Moodle: ' . $enrolledUsersResponse->body());
                            continue; // Saltar este curso en caso de error
                        }

                        $enrolledUsers = $enrolledUsersResponse->json();
                        foreach ($enrolledUsers as $user) {
                            $uniqueStudents->add($user['id']);
                        }
                    }
                }

                // Solo agregar la categoría padre al array de carreras
                $careers[] = [
                    'name' => $categoryName,
                    'students' => $uniqueStudents->unique()->count(),
                    'description' => $categoryDescription,
                ];

                Log::info('Parent category added: ' . $categoryName);
            }

            return $careers;
        });

        return view('web.admin.academic.index', compact('careers'));
    }

    public function create()
    {
        return view('web.admin.academic.create');
    }

    public function store(Request $request)
    {
        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $name = urlencode($request->input('name'));
        $description = urlencode($request->input('description'));

        // Crear categoría en Moodle
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_create_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&categories[0][name]=' . $name
            . '&categories[0][parent]=0' // Categoría padre
            . '&categories[0][description]=' . $description
            . '&categories[0][descriptionformat]=2' // PLAIN
        );

        if ($response->failed()) {
            Log::error('Error creando categoría en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error creando categoría en Moodle']);
        }

        Log::info('Respuesta de Moodle: ' . $response->body());

        // Limpiar caché para actualizar la lista de categorías
        Cache::forget('moodle_careers');

        return redirect()->route('admin.academic.index')->with('success', 'Carrera creada exitosamente.');
    }
}
