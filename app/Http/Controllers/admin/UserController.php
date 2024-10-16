<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        //$query = User::query();
        $query = User::enabled();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
            });
        }
    
        if ($request->has('role')) {
            $role = $request->input('role');
            if ($role != '') {
                $query->where('role', $role);
            }
        }
    
        $users = $query->paginate(10);
        $roles = ['Administrativo', 'Jefe de carrera', 'Docente', 'Superusuario'];
    
        return view('web.admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('web.admin.users.create');
    }

    private function generateRandomPassword($length = 10)
    {
        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%&*';

        $password = substr(str_shuffle($upperCase), 0, 1) .
                    substr(str_shuffle($lowerCase), 0, 1) .
                    substr(str_shuffle($numbers), 0, 1) .
                    substr(str_shuffle($specialChars), 0, 1);

        $allChars = $upperCase . $lowerCase . $numbers . $specialChars;
        $remainingLength = $length - 4;

        $password .= substr(str_shuffle($allChars), 0, $remainingLength);

        return str_shuffle($password);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:Administrativo,Jefe de carrera,Docente,Superusuario',
        ], [
            'name.max' => 'El nombre no debe tener más de 50 caracteres.',
        ]);

        $generatedPassword = $this->generateRandomPassword();

        $nameParts = explode(' ', $request->name, 2);
        $firstname = $nameParts[0];
        $lastname = isset($nameParts[1]) ? $nameParts[1] : '';
        $emailPrefix = explode('@', $request->email)[0];
        $rolePrefix = substr(strtolower($request->role), 0, 3);
        $moodleUser = $rolePrefix . $emailPrefix;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role' => $request->role,
            'moodleuser' => $moodleUser,
        ]);

        try {
            // Crear usuario en Moodle
            $moodlePass = $generatedPassword;
            $apikey = Config::get('app.moodle_api_key_matricular');

            $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_create_users'
                . '&wstoken=' . urldecode($apikey)
                . '&users[0][username]=' . urlencode($moodleUser)
                . '&users[0][password]=' . urlencode($moodlePass)
                . '&users[0][firstname]=' . urlencode($firstname)
                . '&users[0][lastname]=' . urlencode($lastname)
                . '&users[0][email]=' . urlencode($request->email)
                . '&users[0][auth]=manual'
                . '&users[0][idnumber]=' . urlencode($user->id)
                . '&users[0][lang]=es'
            );

            Log::info('Solicitud a Moodle: ', ['request' => [
                'username' => $moodleUser,
                'password' => $moodlePass,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $request->email,
            ], 'response' => $response->body()]);

            if ($response->failed()) {
                Log::error('Error creando usuario en Moodle: ' . $response->body());
                return redirect()->route('admin.users.index')->with('error', 'Error creando usuario en Moodle.');
            }

            $moodleUserData = [
                'moodle_user' => $moodleUser,
                'moodle_pass' => $moodlePass,
            ];

            return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.')->with('moodleUserData', $moodleUserData);
        } catch (\Exception $e) {
            Log::error('Error creando usuario en Moodle: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error creando usuario en Moodle.');
        }
    }
    public function edit(User $user)
    {
        return view('web.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',     // al menos una minuscula
                'regex:/[A-Z]/',     // al menos una mayuscula
                'regex:/[0-9]/',     // al menos un numero
                'regex:/[@$!%*?&]/', // al menos un caracter especial
            ],
            'role' => 'required|string|in:Administrativo,Jefe de carrera,Docente,Superusuario',
        ], [
            'password.min' => 'La contraseña debe contener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un número, una mayúscula, una minúscula o un caracter especial',
            'password.confirmed' => 'La contraseña no es igual a la ingresada.',
            'name.max' => 'El nombre no debe tener más de 50 caracteres.',
        ]);

        // Actualizamos la información en la base de datos local
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'disabled' => $request->has('disabled') ? 0 : 1, // 0 = habilitado, 1 = deshabilitado
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);  // Actualizar usuario en base de datos

        // --- Iniciar proceso de actualización en Moodle ---
        try {
            // Obtener la API key de Moodle
            $apikey = Config::get('app.moodle_api_key_matricular');
            $moodleUser = $user->moodleuser;

            // 1. Buscar al usuario en Moodle por username
            $response = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_get_users'
                . '&wstoken=' . urldecode($apikey)
                . '&criteria[0][key]=username'
                . '&criteria[0][value]=' . urlencode($moodleUser)
            );

            // Verificar si la solicitud falló
            if ($response->failed() || empty($response->json()['users'])) {
                Log::error('Error al buscar usuario en Moodle: ' . $response->body());
                return redirect()->route('admin.users.index')->with('error', 'Usuario no encontrado en Moodle.');
            }

            // Obtener el ID del usuario en Moodle
            $moodleUserId = $response->json()['users'][0]['id'];

            // 2. Preparar datos para la actualización del usuario
            $nameParts = explode(' ', $request->name, 2);
            $firstname = $nameParts[0];
            $lastname = isset($nameParts[1]) ? $nameParts[1] : '';

            // 3. Construir la URL para la actualización del usuario en Moodle
            $updateResponse = Http::post('https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_update_users'
                . '&wstoken=' . urldecode($apikey)
                . '&users[0][id]=' . urlencode($moodleUserId)
                . '&users[0][username]=' . urlencode($moodleUser)
                . '&users[0][firstname]=' . urlencode($firstname)
                . '&users[0][lastname]=' . urlencode($lastname)
                . '&users[0][email]=' . urlencode($request->email)
                . '&users[0][auth]=manual'
                . '&users[0][lang]=es'
                // Si hay contraseña nueva, la incluimos
                . ($request->password ? '&users[0][password]=' . urlencode($request->password) : '')
            );

            // Verificar si la solicitud de actualización falló
            if ($updateResponse->failed()) {
                Log::error('Error actualizando usuario en Moodle: ' . $updateResponse->body());
                return redirect()->route('admin.users.index')->with('error', 'Error actualizando usuario en Moodle.');
            }

            // Registrar éxito en los logs
            Log::info('Usuario actualizado en Moodle: ' . $moodleUser);

        } catch (\Exception $e) {
            Log::error('Error procesando actualización de usuario en Moodle: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error actualizando usuario en Moodle.');
        }

        // --- Si el usuario fue deshabilitado (disabled == 1), eliminarlo en Moodle ---
        if ($user->disabled == 1) {
            try {
                // Verificar la existencia del usuario en Moodle
                $response = Http::retry(3, 1000)->get(
                    'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_get_users'
                    . '&wstoken=' . urldecode($apikey)
                    . '&criteria[0][key]=username'
                    . '&criteria[0][value]=' . urlencode($moodleUser)
                );

                if ($response->failed()) {
                    Log::error('Error al verificar existencia de usuario en Moodle: ' . $response->body());
                    throw new \Exception('Error checking user existence in Moodle');
                }

                $moodleData = $response->json();

                // Verificar si el usuario existe en Moodle y tiene un ID asignado
                if (isset($moodleData['users'][0]['id'])) {
                    $moodleUserId = $moodleData['users'][0]['id']; // Guardar el ID del usuario de Moodle

                    // Eliminar el usuario en Moodle
                    $deleteResponse = Http::retry(3, 1000)->post(
                        'https://campusespi.gcproject.net/webservice/rest/server.php?moodlewsrestformat=json&wsfunction=core_user_delete_users'
                        . '&wstoken=' . urldecode($apikey)
                        . '&userids[0]=' . urlencode($moodleUserId)
                    );

                    if ($deleteResponse->failed()) {
                        Log::error('Error eliminando usuario en Moodle: ' . $deleteResponse->body());
                        throw new \Exception('Error deleting user in Moodle');
                    }

                    Log::info('Usuario eliminado correctamente de Moodle: ' . $moodleUser);
                } else {
                    Log::warning('Usuario no encontrado en Moodle: ' . $moodleUser);
                }
            } catch (\Exception $e) {
                Log::error('Error procesando eliminación de usuario en Moodle: ' . $e->getMessage());
                return redirect()->route('admin.users.index')->with('error', 'Error al eliminar usuario en Moodle.');
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }
    public function destroy(User $user)
    {
        $user->update(['disabled' => 1]);
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function showChangePasswordForm()
    {
        return view('web.admin.users.change_password');
    }

    // Actualizar contraseña del usuario autenticado
    public function updatePassword(Request $request)
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Validar la nueva contraseña
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',     // al menos una minuscula
                'regex:/[A-Z]/',     // al menos una mayuscula
                'regex:/[0-9]/',     // al menos un numero
                'regex:/[@$!%*?&]/', // al menos un caracter especial
            ],
        ], [
            'password.min' => 'La contraseña debe contener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos un número, una mayúscula, una minúscula y un caracter especial',
            'password.confirmed' => 'La contraseña no es igual a la ingresada.',
        ]);

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente.');
    }
}