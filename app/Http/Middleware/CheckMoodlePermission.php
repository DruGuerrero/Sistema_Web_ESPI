<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMoodlePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Asumiendo que solo los superusuarios pueden matricular estudiantes en Moodle
        if (auth()->check() && auth()->user()->role === 'Superusuario') {
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes permisos para realizar esta acción.');
    }
}
