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
    
                // Inicializar el total de estudiantes
                $totalStudents = 0;
    
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
                $uniqueStudents = collect(); // Usamos una colección para asegurar la unicidad de los estudiantes
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
                        $uniqueStudents = $uniqueStudents->merge($enrolledUsers)->unique('id');
                    }
                }
    
                $totalStudents = $uniqueStudents->count();
    
                // Solo agregar la categoría padre al array de carreras
                $careers[] = [
                    'id' => $categoryId, // Incluyendo el id aquí
                    'name' => $categoryName,
                    'students' => $totalStudents,
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
    public function show($id)
    {
        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        // Intentar obtener de la caché primero
        $cacheKey = "moodle_category_$id";
        $cacheDuration = 60; // Cache duration in minutes

        $categoryData = Cache::remember($cacheKey, $cacheDuration, function () use ($apikey, $id) {
            // Obtener la categoría padre
            $categoryResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                . '&wstoken=' . urldecode($apikey)
                . '&criteria[0][key]=id'
                . '&criteria[0][value]=' . $id
            );

            if ($categoryResponse->failed()) {
                Log::error('Error obteniendo categoría de Moodle: ' . $categoryResponse->body());
                abort(404, 'Carrera no encontrada');
            }

            $category = $categoryResponse->json()[0];

            // Obtener categorías hijo
            $subCategoriesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                . '&wstoken=' . urldecode($apikey)
                . '&criteria[0][key]=parent'
                . '&criteria[0][value]=' . $id
            );

            if ($subCategoriesResponse->failed()) {
                Log::error('Error obteniendo subcategorías de Moodle: ' . $subCategoriesResponse->body());
                $subCategories = [];
            } else {
                $subCategories = $subCategoriesResponse->json();
            }

            // Obtener detalles de los cursos y profesores
            $coursesAndProfessors = [];
            foreach ($subCategories as $subCategory) {
                $coursesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field'
                    . '&wstoken=' . urldecode($apikey)
                    . '&field=category'
                    . '&value=' . $subCategory['id']
                );

                if ($coursesResponse->failed()) {
                    Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
                    continue;
                }

                $courses = $coursesResponse->json()['courses'];

                // Inicializar el array de cursos y profesores por subcategoría
                $subCategoryCoursesAndProfessors = [];
                
                foreach ($courses as $course) {
                    $courseId = $course['id'];
                    $professors = [];

                    // Obtener usuarios matriculados en el curso
                    $enrolledUsersResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_enrol_get_enrolled_users'
                        . '&wstoken=' . urldecode($apikey)
                        . '&courseid=' . $courseId
                    );

                    if ($enrolledUsersResponse->failed()) {
                        Log::error('Error obteniendo usuarios matriculados de Moodle: ' . $enrolledUsersResponse->body());
                        continue;
                    }

                    $enrolledUsers = $enrolledUsersResponse->json();

                    // Filtrar solo los usuarios con rol de profesor (id de rol 3 es comúnmente el rol de profesor en Moodle)
                    foreach ($enrolledUsers as $user) {
                        if (isset($user['roles'])) {
                            foreach ($user['roles'] as $role) {
                                if ($role['roleid'] == 3) { // Verificar si el roleid coincide con el de profesor
                                    $professors[] = $user['fullname'];
                                }
                            }
                        }
                    }

                    $subCategoryCoursesAndProfessors[] = [
                        'name' => $course['fullname'],
                        'professor' => implode(', ', $professors) // Unir los nombres de los profesores en una cadena
                    ];
                }

                // Añadir los cursos y profesores agrupados por subcategoría
                $coursesAndProfessors[$subCategory['id']] = $subCategoryCoursesAndProfessors;
            }

            return [
                'category' => $category,
                'subCategories' => $subCategories,
                'coursesAndProfessors' => $coursesAndProfessors
            ];
        });

        return view('web.admin.academic.show', [
            'category' => $categoryData['category'],
            'subCategories' => $categoryData['subCategories'],
            'coursesAndProfessors' => $categoryData['coursesAndProfessors']
        ]);
    }
    public function showSubcategory($id)
    {
        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        // Intentar obtener de la caché primero
        $cacheKey = "moodle_subcategory_$id";
        $cacheDuration = 60; // Cache duration in minutes

        $subcategoryData = Cache::remember($cacheKey, $cacheDuration, function () use ($apikey, $id) {
            // Obtener la subcategoría
            $subcategoryResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                . '&wstoken=' . urldecode($apikey)
                . '&criteria[0][key]=id'
                . '&criteria[0][value]=' . $id
            );

            if ($subcategoryResponse->failed()) {
                Log::error('Error obteniendo subcategoría de Moodle: ' . $subcategoryResponse->body());
                abort(404, 'Año académico no encontrado');
            }

            $subcategory = $subcategoryResponse->json()[0];
            $parentCategoryId = $subcategory['parent'];

            // Obtener los cursos de la subcategoría
            $coursesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field'
                . '&wstoken=' . urldecode($apikey)
                . '&field=category'
                . '&value=' . $id
            );

            if ($coursesResponse->failed()) {
                Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
                $courses = [];
            } else {
                $courses = $coursesResponse->json()['courses'];
            }

            // Obtener detalles de los cursos y profesores
            $coursesAndProfessors = [];
            $uniqueStudents = [];

            foreach ($courses as $course) {
                $courseId = $course['id'];
                $professors = [];

                // Obtener usuarios matriculados en el curso
                $enrolledUsersResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_enrol_get_enrolled_users'
                    . '&wstoken=' . urldecode($apikey)
                    . '&courseid=' . $courseId
                );

                if ($enrolledUsersResponse->failed()) {
                    Log::error('Error obteniendo usuarios matriculados de Moodle: ' . $enrolledUsersResponse->body());
                    continue;
                }

                $enrolledUsers = $enrolledUsersResponse->json();

                // Filtrar solo los usuarios con rol de profesor (id de rol 3 es comúnmente el rol de profesor en Moodle)
                foreach ($enrolledUsers as $user) {
                    $uniqueStudents[$user['id']] = $user['id']; // Asegurarse de que los IDs de los estudiantes son únicos
                    if (isset($user['roles'])) {
                        foreach ($user['roles'] as $role) {
                            if ($role['roleid'] == 3) { // Verificar si el roleid coincide con el de profesor
                                $professors[] = $user['fullname'];
                            }
                        }
                    }
                }

                // Construir URL completa de la imagen del curso
                $courseImage = $course['courseimage'];

                Log::info('Course Image URL: ' . $courseImage);

                $coursesAndProfessors[] = [
                    'name' => $course['fullname'],
                    'image' => $courseImage, // Usar URL completa
                    'professor' => implode(', ', $professors),
                    'description' => strip_tags($course['summary']) // Asumiendo que 'summary' es el campo que contiene la descripción del curso
                ];
            }

            $uniqueStudentsCount = count($uniqueStudents);
            Log::info('Courses and Professors Data: ', $coursesAndProfessors);

            return [
                'subcategory' => $subcategory,
                'coursesAndProfessors' => $coursesAndProfessors,
                'studentsCount' => $uniqueStudentsCount,
                'parentCategoryId' => $parentCategoryId
            ];
        });

        return view('web.admin.academic.show_subcategory', [
            'subcategory' => $subcategoryData['subcategory'],
            'coursesAndProfessors' => $subcategoryData['coursesAndProfessors'],
            'studentsCount' => $subcategoryData['studentsCount'],
            'parentCategoryId' => $subcategoryData['parentCategoryId']
        ]);
    }
}