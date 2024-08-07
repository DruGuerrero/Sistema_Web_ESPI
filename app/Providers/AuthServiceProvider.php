<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define el Gate para 'manage-users' (solo Superusuario)
        Gate::define('manage-users', function ($user) {
            return $user->role === 'Superusuario';
        });

        // Define el Gate para 'manage-students' (Superusuario y Administrativo)
        Gate::define('manage-students', function ($user) {
            return in_array($user->role, ['Superusuario', 'Administrativo']);
        });

        // Define el Gate para 'manage-academic' (Superusuario, Jefe de carrera y Docente)
        Gate::define('manage-academic', function ($user) {
            return in_array($user->role, ['Superusuario', 'Jefe de carrera', 'Docente']);
        });

        // Define el Gate para 'manage-payments' (Superusuario y Administrativo)
        Gate::define('manage-payments', function ($user) {
            return in_array($user->role, ['Superusuario', 'Administrativo']);
        });

        // Define el Gate para 'change-password' (cualquier usuario autenticado)
        Gate::define('change-password', function ($user) {
            return Auth::check();
        });
    }
}
