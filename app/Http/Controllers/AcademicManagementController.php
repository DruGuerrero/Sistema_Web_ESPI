<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use App\Models\Career;
use App\Models\Year;
use App\Models\Course;
use App\Models\MediaFile;
use App\Models\User;
use App\Models\Enrollment;
use PDF;

class AcademicManagementController extends Controller
{
    public function index()
    {
        // Obtener las carreras desde la base de datos
        $careers = Career::all();

        foreach ($careers as $career) {
            $studentCount = Enrollment::where('id_career', $career->id)->count();
            $career->update(['cant_estudiantes' => $studentCount]);
        }

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

        Log::info($response->body());

        if ($response->failed()) {
            Log::error('Error creando categoría en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error creando categoría en Moodle']);
        }

        $moodleCategory = $response->json()[0];
        $moodleCategoryId = $moodleCategory['id'];

        // Crear carrera en la base de datos con el ID de Moodle
        Career::create([
            'nombre' => $request->input('name'),
            'descripcion' => $request->input('description'),
            'cant_estudiantes' => 0, // Valor por defecto
            'id_moodle' => $moodleCategoryId,
        ]);

        // Limpiar caché para actualizar la lista de categorías
        Cache::forget('moodle_careers');

        return redirect()->route('admin.academic.index')->with('success', 'Carrera creada exitosamente.');
    }

    public function show($id)
    {
        $career = Career::with('years.courses.docente', 'mediaFiles')->findOrFail($id);

        return view('web.admin.academic.show', compact('career'));
    }

    public function generateCareerReport($id)
    {
        $career = Career::with(['years.courses.docente', 'students'])->findOrFail($id);
        $students = $career->students;

        $pdf = PDF::loadView('pdf.career_report', compact('career', 'students'));
        $fileName = 'Reporte_Carrera_' . $career->nombre . '.pdf';
        return $pdf->download($fileName);
    }

    public function uploadFile(Request $request, Career $career)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('file')->store('media_files', 'public');

        MediaFile::create([
            'id_career' => $career->id,
            'type' => 'Horario académico',
            'file' => $path,
        ]);

        return redirect()->route('admin.academic.show', $career->id)->with('success', 'Archivo subido exitosamente.');
    }

    public function downloadFile(MediaFile $mediaFile)
    {
        $pathToFile = storage_path('app/public/' . $mediaFile->file);
        return response()->download($pathToFile);
    }

    public function deleteFile(MediaFile $mediaFile)
    {
        $filePath = storage_path('app/public/' . $mediaFile->file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $mediaFile->delete();

        return redirect()->back()->with('success', 'Archivo eliminado exitosamente.');
    }

    public function createYear($career_id)
    {
        return view('web.admin.academic.create_year', compact('career_id'));
    }

    public function storeYear(Request $request)
    {
        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'career_id' => 'required|exists:careers,id',
        ]);

        $name = urlencode($request->input('name'));
        $description = urlencode($request->input('description'));
        $career_id = $request->input('career_id');

        // Obtener la carrera desde la base de datos
        $career = Career::findOrFail($career_id);

        // Crear categoría en Moodle
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_create_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&categories[0][name]=' . $name
            . '&categories[0][parent]=' . $career->id_moodle // Categoría padre es la carrera en Moodle
            . '&categories[0][description]=' . $description
            . '&categories[0][descriptionformat]=2' // PLAIN
        );

        if ($response->failed()) {
            Log::error('Error creando subcategoría en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error creando subcategoría en Moodle']);
        }

        $moodleSubCategory = $response->json()[0];
        $moodleSubCategoryId = $moodleSubCategory['id'];

        // Crear año académico en la base de datos con el ID de Moodle
        Year::create([
            'nombre' => $request->input('name'),
            'descripcion' => $request->input('description'),
            'id_career' => $career_id,
            'cant_estudiantes' => 0, // Valor por defecto
            'id_moodle' => $moodleSubCategoryId,
        ]);
        $year = Career::findOrFail($career_id);

        Log::info('Se creo el año con ID: ' . $moodleSubCategoryId . ', nombre: ' . $name . ' y categoria padre con ID: ' . $career->id_moodle);

        return redirect()->route('admin.academic.show', ['id' => $career_id])->with('success', 'Año académico creado exitosamente.');
    }

    public function showSubCategory($id)
    {
        // Obtener el año académico
        $year = Year::with('courses.docente')->findOrFail($id);

        $apikey = Config::get('app.moodle_api_key_detalles_categorias');

        // Obtener todos los cursos en la subcategoría
        $coursesResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field'
            . '&wstoken=' . urldecode($apikey)
            . '&field=category'
            . '&value=' . $year->id_moodle
        );

        if ($coursesResponse->failed()) {
            Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
            return;
        }

        $courses = $coursesResponse->json()['courses'] ?? [];

        $uniqueStudents = collect();

        // Obtener estudiantes inscritos en cada curso
        foreach ($courses as $course) {
            $enrolledUsersResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_enrol_get_enrolled_users'
                . '&wstoken=' . urldecode($apikey)
                . '&courseid=' . $course['id']
            );

            if ($enrolledUsersResponse->failed()) {
                Log::error('Error obteniendo estudiantes inscritos de Moodle: ' . $enrolledUsersResponse->body());
                continue;
            }

            $enrolledUsers = collect($enrolledUsersResponse->json());

            // Filtrar los estudiantes excluyendo los que tienen el roleid = 3 (profesores)
            $filteredStudents = $enrolledUsers->reject(function ($user) {
                return collect($user['roles'])->contains('roleid', 3);
            });

            // Mezclar los IDs de los estudiantes filtrados y asegurarse de que sean únicos
            $uniqueStudents = $uniqueStudents->merge($filteredStudents->pluck('id'))->unique();
        }

        $year->update(['cant_estudiantes' => $uniqueStudents->count()]);

        // Obtener los cursos asociados al año académico
        $courses = $year->courses->map(function($course) {
            // Obtener la imagen del curso desde la tabla media_files
            $mediaFile = MediaFile::where('id_course', $course->id)->first();
            $imageUrl = $mediaFile ? asset('storage/' . $mediaFile->file) : null;

            return [
                'id' => $course->id,
                'name' => $course->nombre,
                'description' => $course->descripcion,
                'professor' => $course->docente->name,
                'image' => $imageUrl,
            ];
        });

        return view('web.admin.academic.show_subcategory', compact('year', 'courses'));
    }
    
    public function createCourse($subcategory_id)
    {
        $apikey = Config::get('app.moodle_api_key_crear_cursos');

        // Obtener lista de todos los usuarios de Moodle
        $usersResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_get_users'
            . '&wstoken=' . urldecode($apikey)
            . '&criteria[0][key]=firstname'
            . '&criteria[0][value]=%'
        );

        if ($usersResponse->failed()) {
            Log::error('Error obteniendo lista de usuarios de Moodle: ' . $usersResponse->body());
            return redirect()->back()->withErrors(['error' => 'Error obteniendo lista de usuarios de Moodle']);
        }

        $response = $usersResponse->json();

        if (!isset($response['users']) || empty($response['users'])) {
            return redirect()->back()->withErrors(['error' => 'No se encontraron usuarios en Moodle.']);
        }

        // Filtrar los usuarios cuyo nombre de usuario comienza con "doc"
        $moodleTeachers = array_filter($response['users'], function ($user) {
            return strpos($user['username'], 'doc') === 0;
        });

        // Obtener lista de usuarios con el rol "Docente" desde la base de datos
        $dbTeachers = User::where('role', 'Docente')->get();

        return view('web.admin.academic.create_course', compact('moodleTeachers', 'dbTeachers', 'subcategory_id'));
    }
    
    public function storeCourse(Request $request)
    {
        $apikey = Config::get('app.moodle_api_key_crear_cursos');

        $request->validate([
            'fullname' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'teacher' => 'required|integer',
        ]);

        $yearId = $request->input('subcategory_id');
        $fullname = $request->input('fullname');
        $shortname = $fullname;
        $summary = $request->input('description');
        $teacherId = $request->input('teacher');

        // Obtener el año académico desde la base de datos
        $year = Year::findOrFail($yearId);
        $subcategory_id = $year->id_moodle;

        // Registrar el ID de la subcategoría para depuración
        Log::info('Intentando crear un curso en la subcategoría de Moodle con ID: ' . $subcategory_id);

        // Verificar si la subcategoría existe en Moodle
        $checkCategoryResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&criteria[0][key]=id'
            . '&criteria[0][value]=' . $subcategory_id
        );

        if ($checkCategoryResponse->failed() || empty($checkCategoryResponse->json())) {
            Log::error('La subcategoría especificada no existe en Moodle o no se pudo verificar: ' . $checkCategoryResponse->body());
            return redirect()->back()->withErrors(['error' => 'La subcategoría especificada no existe en Moodle o no se pudo verificar.']);
        }

        // Obtener el usuario docente desde la base de datos
        $teacher = User::findOrFail($teacherId);

        // Crear curso en Moodle
        $createCourseResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_create_courses'
            . '&wstoken=' . urldecode($apikey)
            . '&courses[0][fullname]=' . urlencode($fullname)
            . '&courses[0][shortname]=' . urlencode($shortname)
            . '&courses[0][categoryid]=' . $subcategory_id
            . '&courses[0][summary]=' . urlencode($summary)
            . '&courses[0][summaryformat]=2'
            . '&courses[0][maxbytes]=20971520'
        );

        if ($createCourseResponse->failed()) {
            Log::error('Error creando curso en Moodle: ' . $createCourseResponse->body());
            return redirect()->back()->withErrors(['error' => 'Error creando curso en Moodle']);
        }

        $courseResponseBody = $createCourseResponse->json();
        Log::info('Respuesta de creación de curso en Moodle:', $courseResponseBody);

        if (!isset($courseResponseBody[0])) {
            return redirect()->back()->withErrors(['error' => 'Respuesta inesperada de Moodle al crear curso.']);
        }

        $course = $courseResponseBody[0];
        $courseId = $course['id'];

        // Obtener el id del usuario en Moodle utilizando su username (moodleuser)
        $getUserResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_get_users'
            . '&wstoken=' . urldecode($apikey)
            . '&criteria[0][key]=username'
            . '&criteria[0][value]=' . urlencode($teacher->moodleuser)
        );

        if ($getUserResponse->failed() || empty($getUserResponse->json()['users'])) {
            Log::error('Error obteniendo el ID del usuario en Moodle: ' . $getUserResponse->body());
            return redirect()->back()->withErrors(['error' => 'Error obteniendo el ID del usuario en Moodle']);
        }

        $moodleUserId = $getUserResponse->json()['users'][0]['id'];

        //Log::info('ID del usuario a asignar al curso: ', $moodleUserId);

        // Asignar docente al curso en Moodle
        $enrollResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=enrol_manual_enrol_users'
            . '&wstoken=' . urldecode($apikey)
            . '&enrolments[0][roleid]=3'
            . '&enrolments[0][userid]=' . $moodleUserId
            . '&enrolments[0][courseid]=' . $courseId
        );

        if ($enrollResponse->failed()) {
            Log::error('Error asignando docente al curso en Moodle: ' . $enrollResponse->body());
            return redirect()->back()->withErrors(['error' => 'Error asignando docente al curso en Moodle']);
        }

        // Subir imagen al servidor y guardar referencia en la base de datos
        $image = $request->file('image');
        $path = $image->store('course_images', 'public');

        // Crear curso en la base de datos con el ID de Moodle
        $course = Course::create([
            'nombre' => $request->input('fullname'),
            'descripcion' => $request->input('description'),
            'id_docente' => $teacherId,
            'id_year' => $yearId,
            'id_moodle' => $courseId,
        ]);

        // Guardar referencia del archivo en la tabla media_files con el tipo "foto_de_curso"
        MediaFile::create([
            'id_course' => $course->id,
            'file' => $path,
            'type' => 'foto_de_curso',
        ]);

        Log::info('Se creó el curso con los siguientes datos:', [
            'courseId' => $courseId,
            'fullname' => $fullname,
            'shortname' => $shortname,
            'subcategory_id' => $subcategory_id,
            'moodleuser' => $teacher->moodleuser,
        ]);

        return redirect()->route('admin.academic.show_subcategory', ['id' => $yearId])->with('success', 'Curso creado exitosamente.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Eliminar el curso de Moodle
        $apikey = Config::get('app.moodle_api_key_crear_cursos');
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_delete_courses'
            . '&wstoken=' . urldecode($apikey)
            . '&courseids[0]=' . urlencode($course->id_moodle)
        );

        if ($response->failed()) {
            Log::error('Error eliminando curso en Moodle: ' . $response->body());
            return response()->json(['error' => 'Error eliminando curso en Moodle.'], 500);
        }

        // Eliminar el curso de la base de datos
        $course->delete();

        return response()->json(['success' => 'Elemento eliminado exitosamente.']);
    }
    public function destroyYear($id)
    {
        $year = Year::findOrFail($id);

        // Eliminar la categoría de Moodle de forma recursiva
        $apikey = Config::get('app.moodle_api_key_crear_cursos');
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_delete_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&categories[0][id]=' . urlencode($year->id_moodle)
            . '&categories[0][recursive]=1'
        );

        if ($response->failed()) {
            Log::error('Error eliminando categoría en Moodle: ' . $response->body());
            return response()->json(['error' => 'Error eliminando categoría en Moodle.'], 500);
        }

        // Eliminar el año y los cursos asociados en la base de datos
        $year->courses()->delete();
        $year->delete();

        return response()->json(['success' => 'Año académico eliminado exitosamente.']);
    }

    public function updateSubCategory(Request $request, $id)
    {
        $apikey = Config::get('app.moodle_api_key_crear_cursos');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $year = Year::findOrFail($id);

        $name = $request->input('name');
        $description = $request->input('description');

        // Actualizar categoría en Moodle
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_update_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&categories[0][id]=' . $year->id_moodle
            . '&categories[0][name]=' . urlencode($name)
            . '&categories[0][description]=' . urlencode($description)
            . '&categories[0][descriptionformat]=2' // PLAIN
        );

        if ($response->failed()) {
            Log::error('Error actualizando subcategoría en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error actualizando subcategoría en Moodle']);
        }

        // Actualizar el año académico en la base de datos
        $year->update([
            'nombre' => $name,
            'descripcion' => $description,
        ]);

        return redirect()->route('admin.academic.show_subcategory', ['id' => $year->id])->with('success', 'Año académico actualizado exitosamente.');
    }

    public function updateCategory(Request $request, $id)
    {
        $apikey = Config::get('app.moodle_api_key_crear_cursos');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $career = Career::findOrFail($id);

        $name = $request->input('name');
        $description = $request->input('description');

        // Actualizar categoría en Moodle
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_update_categories'
            . '&wstoken=' . urldecode($apikey)
            . '&categories[0][id]=' . $career->id_moodle
            . '&categories[0][name]=' . urlencode($name)
            . '&categories[0][description]=' . urlencode($description)
            . '&categories[0][descriptionformat]=2' // PLAIN
        );

        if ($response->failed()) {
            Log::error('Error actualizando categoría en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error actualizando categoría en Moodle']);
        }

        // Actualizar la carrera en la base de datos
        $career->update([
            'nombre' => $name,
            'descripcion' => $description,
        ]);

        return redirect()->route('admin.academic.show', ['id' => $career->id])->with('success', 'Carrera actualizada exitosamente.');
    }
    public function showCourse($id)
    {
        $course = Course::with('docente', 'mediaFile')->findOrFail($id);
    
        // Clave para el caché
        $cacheKey = 'course_' . $course->id . '_students';
    
        // Intentar obtener los estudiantes y sus calificaciones del caché
        $students = Cache::remember($cacheKey, 60 * 60, function () use ($course, $cacheKey) {
            $apikey = Config::get('app.moodle_api_key_info_estudiantes');
            $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_enrol_get_enrolled_users'
                . '&wstoken=' . urldecode($apikey)
                . '&courseid=' . $course->id_moodle
            );
    
            if ($response->failed()) {
                Log::error('Error obteniendo estudiantes inscritos desde Moodle: ' . $response->body());
                return collect(); // Colección vacía en caso de error
            } else {
                $students = collect($response->json());
            }
    
            $students = $students->reject(function ($student) {
                return collect($student['roles'])->contains('roleid', 3);
            });
    
            // Obtener calificaciones promedio para cada estudiante
            $students = $students->map(function ($student) use ($course, $apikey) {
                $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=gradereport_user_get_grade_items'
                    . '&wstoken=' . urldecode($apikey)
                    . '&courseid=' . $course->id_moodle
                    . '&userid=' . $student['id']
                );
    
                if ($response->failed()) {
                    Log::error('Error obteniendo calificaciones desde Moodle: ' . $response->body());
                    $student['average_grade'] = 0; // Valor por defecto en caso de error
                } else {
                    $gradeItems = collect($response->json()['usergrades'][0]['gradeitems'])
                        ->reject(function ($item) {
                            return $item['itemtype'] === 'course';
                        });
    
                    $totalGrade = $gradeItems->sum('graderaw');
                    $averageGrade = $gradeItems->count() > 0 ? $totalGrade / $gradeItems->count() : 0;
                    $student['average_grade'] = round($averageGrade, 2); // Redondear a dos decimales
    
                    // Guardar los nombres de las tareas y sus notas
                    $student['grades'] = $gradeItems->pluck('graderaw', 'itemname')->toArray();
    
                    // Agregar logs para verificar las calificaciones
                    Log::info('Calificaciones para el estudiante ' . $student['fullname'] . ': ' . $gradeItems->pluck('graderaw', 'itemname')->toJson());
                }
    
                return $student;
            });
            
            return $students;
        });
    
        return view('web.admin.academic.show_course', compact('course', 'students'));
    }

    public function updateCourse(Request $request, $id)
    {
        $apikey = Config::get('app.moodle_api_key_info_estudiantes');

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course = Course::findOrFail($id);

        $name = $request->input('name');
        $description = $request->input('description');

        // Actualizar curso en Moodle
        $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_update_courses'
            . '&wstoken=' . urldecode($apikey)
            . '&courses[0][id]=' . $course->id_moodle
            . '&courses[0][fullname]=' . urlencode($name)
            . '&courses[0][shortname]=' . urlencode(substr($name, 0, 4) . $course->id_year)
            . '&courses[0][summary]=' . urlencode($description)
            . '&courses[0][summaryformat]=2' // PLAIN
        );

        if ($response->failed()) {
            Log::error('Error actualizando curso en Moodle: ' . $response->body());
            return redirect()->back()->withErrors(['error' => 'Error actualizando curso en Moodle']);
        }

        // Actualizar el curso en la base de datos
        $course->update([
            'nombre' => $name,
            'descripcion' => $description,
        ]);

        Cache::forget('course_' . $course->id . '_students');

        return redirect()->route('admin.academic.show_course', ['id' => $course->id])->with('success', 'Curso actualizado exitosamente.');
    }

    public function generateCourseReport($id)
    {
        $course = Course::with('docente')->findOrFail($id);
    
        // Clave para el caché
        $cacheKey = 'course_' . $course->id . '_students';
    
        // Obtener los estudiantes desde el caché
        $students = Cache::get($cacheKey, collect());
    
        // Obtener las tareas del primer estudiante como referencia
        $tasks = $students->first()['grades'] ?? [];
    
        $pdf = PDF::loadView('pdf.course_report', compact('course', 'students', 'tasks'));
    
        $fileName = 'Reporte_Curso_' . $course->nombre . '.pdf';
        return $pdf->download($fileName);
    }

    public function generateTeacherReportByCourse($course_id)
    {
        // Obtener el curso con su docente
        $course = Course::with('docente')->findOrFail($course_id);
    
        $teacher = $course->docente;
    
        if (!$teacher) {
            return redirect()->back()->withErrors(['error' => 'No se encontró el docente asignado a este curso.']);
        }
    
        // Generar el PDF usando una plantilla Blade
        $pdf = PDF::loadView('pdf.teacher_report', compact('teacher', 'course'));
    
        $fileName = 'Reporte_Docente_' . $teacher->name . '.pdf';
        return $pdf->download($fileName);
    }    

    public function refreshCache($id)
    {
        $course = Course::findOrFail($id);

        //invalidar cache
        Cache::forget('course_' . $course->id . '_students');

        return redirect()->route('admin.academic.show_course', ['id' => $course->id])->with('success', 'Caché actualizado exitosamente.');
    }
}