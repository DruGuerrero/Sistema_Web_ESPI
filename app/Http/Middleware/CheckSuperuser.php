<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperuser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'Superusuario') {
            return $next($request);
        }

        return redirect('/'); // Redirige a la página principal o muestra un error
    }
}
