<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
        ]);

        $data = $request->all();
        $data['matricula'] = 'NO'; // Asignar "NO" por defecto

        Student::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Estudiante creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Obtener la foto tipo carnet del estudiante
        $photo = $student->mediaFiles()->where('type', 'foto_tipo_carnet')->first();
        $photoUrl = $photo ? asset('storage/' . $photo->file) : asset('/vendor/adminlte/dist/img/default_user.png');

        $files = $student->mediaFiles;

        return view('web.admin.students.show', compact('student', 'photoUrl', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('web.admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            'carnet_escaneado' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'foto_tipo_carnet' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);

        $data = $request->all();
        $data['matricula'] = $student->matricula; // Mantener el valor existente
        $data['disabled'] = $request->has('disabled') ? 0 : 1;

        $student->update($data);

        if ($request->hasFile('carnet_escaneado')) {
            $path = $request->file('carnet_escaneado')->store('media_files', 'public');
            MediaFile::create([
                'student_id' => $student->id,
                'type' => 'carnet_escaneado',
                'file' => $path,
            ]);
        }
        // Manejar la lógica de reemplazo de la foto tipo carnet
        if ($request->hasFile('foto_tipo_carnet')) {
            // Verificar si ya hay una foto tipo carnet existente
            $existingPhoto = $student->mediaFiles()->where('type', 'foto_tipo_carnet')->first();
            if ($existingPhoto) {
                // Eliminar el archivo existente del sistema de archivos
                $existingFilePath = storage_path('app/public/' . $existingPhoto->file);
                if (file_exists($existingFilePath)) {
                    unlink($existingFilePath);
                }
                // Eliminar el registro de la base de datos
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
        // Eliminar el archivo del sistema de archivos
        $filePath = storage_path('app/public/' . $mediaFile->file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Eliminar el registro de la base de datos
        $mediaFile->delete();

        return redirect()->back()->with('success', 'Archivo eliminado exitosamente.');
    }

    public function matriculate(Request $request, Student $student)
    {
        try {
            // Generar usuario de moodle
            $moodleUser = strtolower(substr($student->nombre, 0, 2) . $student->apellido_paterno . substr($student->apellido_materno, 0, 1));
            $moodleUser = str_replace('ñ', 'n', $moodleUser);

            // Generar contraseña de moodle
            $moodlePass = ucfirst(substr($student->nombre, 0, 2)) . strtolower($student->apellido_paterno) . substr($student->num_carnet, 0, 3) . '*';
            $moodlePass = str_replace('ñ', 'n', $moodlePass);


            // Encriptar la contraseña
            $encryptedMoodlePass = Hash::make($moodlePass);


            // Crear cuenta en Moodle
            $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_create_users&wstoken=838e1a662579f855f5d1ef5e1c3da72e'
                . '&users[0][username]=' . urlencode($moodleUser)
                . '&users[0][password]=' . urlencode($moodlePass)
                . '&users[0][firstname]=' . urlencode($student->nombre)
                . '&users[0][lastname]=' . urlencode($student->apellido_paterno . ' ' . $student->apellido_materno)
                . '&users[0][email]=' . urlencode($student->email)
                . '&users[0][auth]=manual'
                . '&users[0][idnumber]=' . urlencode($student->num_carnet)
                . '&users[0][lang]=es'
            );

            // Verificar respuesta de Moodle
            if ($response->failed()) {
                Log::error('Error creando usuario en Moodle: ' . $response->body());
                return response()->json(['error' => 'Error creating user in Moodle'], 500);
            }

            // Actualizar estudiante
            $student->moodle_user = $moodleUser;
            $student->moodle_pass = $encryptedMoodlePass;
            $student->matricula = 'SI';
            $student->save();
            
            $createdUser = $response->json()[0];
            $userId = $createdUser['id'];

            // ID de la categoría de cursos
            $categoryId = 3; // Cambia esto al ID de la categoría deseada

            // Obtener todos los cursos en la categoría
            $coursesResponse = Http::get('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_course_get_courses_by_field&wstoken=838e1a662579f855f5d1ef5e1c3da72e'
                . '&field=category'
                . '&value=' . $categoryId
            );

            if ($coursesResponse->failed()) {
                Log::error('Error obteniendo cursos de Moodle: ' . $coursesResponse->body());
                return response()->json(['error' => 'Error fetching courses from Moodle'], 500);
            }

            $courses = $coursesResponse->json()['courses'];

            // Preparar enrolments
            $enrolments = [];
            foreach ($courses as $course) {
                $enrolments[] = [
                    'roleid' => 5, // Role ID for student
                    'userid' => $userId,
                    'courseid' => $course['id']
                ];
            }

            // Enroll user in each course
            foreach($enrolments as $enrol){
                $enrolResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=enrol_manual_enrol_users&wstoken=838e1a662579f855f5d1ef5e1c3da72e'
                    . '&enrolments[0][roleid]=' . urlencode($enrol['roleid'])
                    . '&enrolments[0][userid]=' . urlencode($enrol['userid'])
                    . '&enrolments[0][courseid]=' . urlencode($enrol['courseid'])
                );
                if ($enrolResponse->failed()) {
                    Log::error('Error enrolando usuario en cursos de Moodle: ' . $enrolResponse->body());
                    return response()->json(['error' => 'Error enrolling user in Moodle courses'], 500);
                }
            }

            // Retornar los datos generados (sin encriptar la contraseña)
            return response()->json([
                'moodle_user' => $moodleUser,
                'moodle_pass' => $moodlePass,
            ]);
        } catch (\Exception $e) {
            // Log::error('Error matriculating student: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
