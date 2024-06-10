<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        $timeout = config('session.lifetime') * 60; // Obtener el tiempo de vida en segundos

        if (Auth::check()) {
            $lastActivity = session('last_activity', now()->timestamp); // Obtener last_activity de la sesión

            if (now()->timestamp - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('message', 'You have been logged out due to inactivity.');
            }

            session(['last_activity' => now()->timestamp]); // Actualizar last_activity
        }

        return $next($request);
    }
}
