<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\MediaFile;
use Illuminate\Http\Request;

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

        return view('web.admin.students.index', compact('students'));
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
        return view('web.admin.students.show', compact('student'));
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

        $student->update($data);

        if ($request->hasFile('carnet_escaneado')) {
            $path = $request->file('carnet_escaneado')->store('media_files');
            MediaFile::create([
                'student_id' => $student->id,
                'type' => 'carnet_escaneado',
                'file' => $path,
            ]);
        }

        if ($request->hasFile('foto_tipo_carnet')) {
            $path = $request->file('foto_tipo_carnet')->store('media_files');
            MediaFile::create([
                'student_id' => $student->id,
                'type' => 'foto_tipo_carnet',
                'file' => $path,
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Estudiante actualizado exitosamente.');
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
