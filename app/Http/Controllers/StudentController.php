<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\MediaFile;
use App\Models\Career;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido_paterno', 'like', "%{$search}%")
                      ->orWhere('apellido_materno', 'like', "%{$search}%")
                      ->orWhere('num_carnet', 'like', "%{$search}%");
            });
        }

        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'Matriculado') {
                $query->where('matricula', 'SI');
            } elseif ($filter === 'No matriculado') {
                $query->where('matricula', 'NO');
            }
        }

        $students = $query->paginate(10);

        return view('web.admin.students.index', [
            'students' => $students,
            'index' => ($students->currentPage() - 1) * $students->perPage()
        ]);
    }
    public function create()
    {
        $careers = Career::all(); // Obtener todas las carreras
        return view('web.admin.students.create', compact('careers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'num_carnet' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'ciudad_domicilio' => 'required|string|max:255',
            'num_celular' => 'required|string|max:255',
            'nombre_tutor' => 'nullable|string|max:255',
            'celular_tutor' => 'nullable|string|max:255',
            'ciudad_tutor' => 'nullable|string|max:255',
            'parentesco' => 'nullable|string|max:255',
            'career_id' => 'required|exists:careers,id', // Validación para carrera
        ]);

        $data = $request->all();
        $data['matricula'] = 'NO'; // Asignar "NO" por defecto

        $student = Student::create($data);

        Enrollment::create([
            'id_student' => $student->id,
            'id_career' => $request->career_id,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Estudiante creado exitosamente.');
    }

    public function show(Student $student)
    {
        // Obtener la foto tipo carnet del estudiante
        $photo = $student->mediaFiles()->where('type', 'foto_tipo_carnet')->first();
        $photoUrl = $photo ? asset('storage/' . $photo->file) : asset('/vendor/adminlte/dist/img/default_user.png');

        $files = $student->mediaFiles;

        return view('web.admin.students.show', compact('student', 'photoUrl', 'files'));
    }

    public function edit(Student $student)
    {
        return view('web.admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'num_carnet' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'ciudad_domicilio' => 'required|string|max:255',
            'num_celular' => 'required|string|max:255',
            'nombre_tutor' => 'nullable|string|max:255',
            'celular_tutor' => 'nullable|string|max:255',
            'ciudad_tutor' => 'nullable|string|max:255',
            'parentesco' => 'nullable|string|max:255',
            'documentos_estudiante' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'foto_tipo_carnet' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        $data = $request->all();
        $data['matricula'] = $student->matricula;
        $data['disabled'] = $request->has('disabled') ? 0 : 1;

        $student->update($data);

        if ($request->hasFile('documentos_estudiante')) {
            $path = $request->file('documentos_estudiante')->store('media_files', 'public');
            MediaFile::create([
                'student_id' => $student->id,
                'type' => 'documentos_estudiante',
                'file' => $path,
            ]);
        }
        if ($request->hasFile('foto_tipo_carnet')) {
            // Verificar si ya hay una foto tipo carnet existente
            $existingPhoto = $student->mediaFiles()->where('type', 'foto_tipo_carnet')->first();
            if ($existingPhoto) {
                // Eliminar el archivo existente del sistema de archivos
                $existingFilePath = storage_path('app/public/' . $existingPhoto->file);
                if (file_exists($existingFilePath)) {
                    unlink($existingFilePath);
                }
                $existingPhoto->delete();
            }

            // Guardar la nueva foto tipo carnet
            $path = $request->file('foto_tipo_carnet')->store('media_files', 'public');
            MediaFile::create([
                'student_id' => $student->id,
                'type' => 'foto_tipo_carnet',
                'file' => $path,
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function download(MediaFile $mediaFile)
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

    function generateRandomPassword($length = 10)
    {
        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%&*';

        // Ensure each required character type is included
        $password = substr(str_shuffle($upperCase), 0, 1) .
                    substr(str_shuffle($lowerCase), 0, 1) .
                    substr(str_shuffle($numbers), 0, 1) .
                    substr(str_shuffle($specialChars), 0, 1);

        // Fill the rest of the password length with a random selection of all characters
        $allChars = $upperCase . $lowerCase . $numbers . $specialChars;
        $remainingLength = $length - 4; // Already added 4 chars above

        // Randomly select characters for the remaining length
        $password .= substr(str_shuffle($allChars), 0, $remainingLength);

        // Shuffle the final password to mix the predefined characters
        return str_shuffle($password);
    }

    public function matriculate(Request $request, Student $student)
    {
        $moodleUser = null;
        $userId = null;
    
        try {
            DB::beginTransaction();
            Log::info('Transaction started for student matriculation', ['student_id' => $student->id]);
    
            // Generar usuario de Moodle
            $nombre = str_replace('ñ', 'n', $student->nombre);
            $apellidopaterno = str_replace('ñ', 'n', $student->apellido_paterno);
            $moodleUser = strtolower(substr($nombre, 0, 2) . $apellidopaterno . substr($student->apellido_materno, 0, 1));
            $moodleUser .= substr($student->num_carnet, -2) . substr($student->num_celular, -2);
            $moodleUser = str_replace('ñ', 'n', $moodleUser);
    
            Log::info('Generated Moodle username', ['moodle_user' => $moodleUser]);
    
            // Generar contraseña de Moodle
            $moodlePass = $this->generateRandomPassword(10); // Specify the length you want
    
            Log::info('Generated Moodle password');
    
            // Encriptar la contraseña para almacenarla
            $encryptedMoodlePass = Hash::make($moodlePass);
    
            Log::info('Encrypted Moodle password for storage');
    
            $apikey = Config::get('app.moodle_api_key_matricular');
            Log::info('Retrieved Moodle API key');
    
            // Verificar si el usuario ya existe
            Log::info('Checking if user already exists in Moodle');
            $response = Http::retry(3, 1000)
                ->get(
                    'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_get_users'
                    . '&wstoken=' . urldecode($apikey)
                    . '&criteria[0][key]=username'
                    . '&criteria[0][value]=' . urlencode($moodleUser)
                );
    
            if ($response->failed()) {
                Log::error('Error al verificar existencia de usuario en Moodle: ' . $response->body());
                throw new \Exception('Error checking user existence in Moodle');
            }
    
            $responseJson = $response->json();
    
            if (!isset($responseJson['users'])) {
                Log::error('Unexpected response from Moodle when checking user existence: ' . $response->body());
                throw new \Exception('Unexpected response structure from Moodle when checking user existence');
            }
    
            $users = $responseJson['users'];
    
            if (!empty($users)) {
                // Usuario ya existe, obtener el ID
                $userId = $users[0]['id'];
                Log::info('User already exists in Moodle', ['user_id' => $userId]);
            } else {
                //Usuario no existe, Crear cuenta en Moodle
                Log::info('Creating new user in Moodle');
                $response = Http::retry(3, 1000)
                    ->post(
                        'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_create_users'
                        . '&wstoken=' . urldecode($apikey)
                        . '&users[0][username]=' . urlencode($moodleUser)
                        . '&users[0][password]=' . urlencode($moodlePass)
                        . '&users[0][firstname]=' . urlencode($student->nombre)
                        . '&users[0][lastname]=' . urlencode($student->apellido_paterno . ' ' . $student->apellido_materno)
                        . '&users[0][email]=' . urlencode($student->email)
                        . '&users[0][auth]=manual'
                        . '&users[0][idnumber]=' . urlencode($student->num_carnet)
                        . '&users[0][lang]=es'
                    );
    
                if ($response->failed()) {
                    Log::error('Error creando usuario en Moodle: ' . $response->body());
                    throw new \Exception('Error creating user in Moodle');
                }
    
                $createdUser = $response->json();
    
                if (!isset($createdUser[0]['id'])) {
                    Log::error('Unexpected response from Moodle when creating user: ' . $response->body());
                    throw new \Exception('Unexpected response structure from Moodle when creating user');
                }
    
                $userId = $createdUser[0]['id'];
                Log::info('User created successfully in Moodle', ['user_id' => $userId]);
            }
    
            // Actualizar estudiante con los datos de Moodle
            $student->moodle_user = $moodleUser;
            $student->moodle_pass = $encryptedMoodlePass;
            $student->matricula = 'SI';
            $student->save();
    
            Log::info('Student record updated with Moodle credentials', ['student_id' => $student->id]);
    
            $career = $student->careers->first();
            if (!$career) {
                throw new \Exception('No se encontró la carrera del estudiante.');
            }
    
            Log::info('Career found for student', ['career_id' => $career->id]);
    
            // Obtener la subcategoría "Primer año"
            Log::info('Fetching subcategory "Primer año" for career', ['career_id' => $career->id]);
            $categoryResponse = Http::retry(3, 1000)
                ->get(
                    'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_categories'
                    . '&wstoken=' . urldecode($apikey)
                    . '&criteria[0][key]=parent'
                    . '&criteria[0][value]=' . $career->id_moodle
                    . '&criteria[1][key]=name'
                    . '&criteria[1][value]=Primer año'
                );
    
            if ($categoryResponse->failed()) {
                Log::error('Error obteniendo subcategoría de Moodle: ' . $categoryResponse->body());
                throw new \Exception('Error fetching subcategory from Moodle');
            }
    
            $categories = $categoryResponse->json();
            if (empty($categories)) {
                throw new \Exception('No se encontró la subcategoría "Primer año" para la carrera seleccionada.');
            }
    
            $subcategory = $categories[0];
            Log::info('Subcategory "Primer año" found', ['subcategory_id' => $subcategory['id']]);
    
            // Obtener todos los cursos en la subcategoria "Primer año"
            Log::info('Fetching courses in subcategory "Primer año"', ['subcategory_id' => $subcategory['id']]);
            $coursesResponse = Http::retry(3, 1000)
                ->get(
                    'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field'
                    . '&wstoken=' . urldecode($apikey)
                    . '&field=category'
                    . '&value=' . $subcategory['id']
                );
    
            if ($coursesResponse->failed()) {
                Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
                throw new \Exception('Error fetching courses from Moodle');
            }
    
            $courses = $coursesResponse->json()['courses'];
            Log::info('Courses retrieved successfully', ['course_count' => count($courses)]);
    
            $enrolments = [];
            foreach ($courses as $course) {
                $enrolments[] = [
                    'roleid' => 5, // Role ID para student
                    'userid' => $userId,
                    'courseid' => $course['id']
                ];
            }
    
            Log::info('Prepared enrolment data for courses', ['enrolment_count' => count($enrolments)]);
    
            // Matricular usuario en cada curso
            foreach ($enrolments as $index => $enrol) {
                Log::info('Enrolling user in course', ['course_id' => $enrol['courseid'], 'user_id' => $userId]);
                $enrolResponse = Http::retry(3, 1000)
                    ->post(
                        'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=enrol_manual_enrol_users'
                        . '&wstoken=' . urldecode($apikey)
                        . '&enrolments[' . $index . '][roleid]=' . urlencode($enrol['roleid'])
                        . '&enrolments[' . $index . '][userid]=' . urlencode($enrol['userid'])
                        . '&enrolments[' . $index . '][courseid]=' . urlencode($enrol['courseid'])
                    );
    
                if ($enrolResponse->failed()) {
                    Log::error('Error enrolando usuario en cursos de Moodle: ' . $enrolResponse->body());
                    throw new \Exception('Error enrolling user in Moodle courses at course index: ' . $index);
                }
    
                Log::info('User enrolled successfully in course', ['course_id' => $enrol['courseid'], 'user_id' => $userId]);
            }
    
            DB::commit();
            Log::info('Transaction committed successfully for student matriculation', ['student_id' => $student->id]);
    
            return response()->json([
                'moodle_user' => $moodleUser,
                'moodle_pass' => $moodlePass,
            ]);
        } catch (ConnectException $e) {
            // Manejo de errores de conexión
            DB::rollback();
            Log::error('Network error occurred: ' . $e->getMessage(), ['student_id' => $student->id]);
    
            return response()->json(['error' => 'Se ha producido un problema de conexión a Internet. Por favor, inténtalo de nuevo.'], 500);
    
        } catch (\Exception $e) {
            // Hacer rollback de la transacción en caso de error
            DB::rollback();
            Log::error('Error matriculating student: ' . $e->getMessage(), ['student_id' => $student->id]);
    
            // Intentar eliminar el usuario de Moodle si ya fue creado pero no pudo ser matriculado
            if ($userId) {
                try {
                    Log::info('Attempting to delete user from Moodle', ['user_id' => $userId]);
                    $deleteResponse = Http::retry(3, 1000)
                        ->post(
                            'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_delete_users'
                            . '&wstoken=' . urldecode($apikey)
                            . '&userids[0]=' . $userId
                        );
    
                    if ($deleteResponse->failed()) {
                        Log::error('Error eliminando usuario en Moodle: ' . $deleteResponse->body());
                    } else {
                        Log::info('User deleted successfully from Moodle', ['user_id' => $userId]);
                    }
                } catch (\Exception $deleteException) {
                    Log::error('Error eliminando usuario en Moodle (catch): ' . $deleteException->getMessage(), ['user_id' => $userId]);
                }
            }
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}