<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('web.admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('web.admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/', // al menos una minuscula
                'regex:/[A-Z]/', // al menos una mayuscula
                'regex:/[0-9]/', // al menos un numero
                'regex:/[@$!%*?&]/', // al menos un caracter especial
            ],
            'role' => 'required|string|in:Administrativo,Jefe de carrera,Docente,Estudiante,Superusuario',
        ], [
            'password.min' => 'La contraseña debe contener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un número, una mayúscula, una minúscula o un caracter especial',
            'password.confirmed' => 'La contraseña no es igual a la ingresada.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        return view('web.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/', // al menos una minuscula
                'regex:/[A-Z]/', // al menos una mayuscula
                'regex:/[0-9]/', // al menos un numero
                'regex:/[@$!%*?&]/', // al menos un caracter especial
            ],
            'role' => 'required|string|in:Administrativo,Jefe de carrera,Docente,Estudiante,Superusuario',
        ], [
            'password.min' => 'La contraseña debe contener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un número, una mayúscula, una minúscula o un caracter especial',
            'password.confirmed' => 'La contraseña no es igual a la ingresada.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
