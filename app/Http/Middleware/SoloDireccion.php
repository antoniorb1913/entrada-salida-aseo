<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SoloDireccion
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            
            // Lo mandamos a la selección de cursos con un error
            return redirect()->route('acceso')->with('error', 'No tienes permisos para acceder a la zona de administración.');
        }

        // Si es admin, le dejamos pasar
        return $next($request);
    }
}