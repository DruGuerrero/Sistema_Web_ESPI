<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckJefeDeCarrera
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'Jefe de carrera') {
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}