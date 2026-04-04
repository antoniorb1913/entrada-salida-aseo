<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloDireccion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está logueado y es profesor, lo echamos fuera de las consultas
        if ($request->user() && $request->user()->rol === 'profesor') {
            return redirect()->route('acceso')->with('error', 'Acceso denegado. Solo dirección puede consultar.');
        }

        // Si es admin o consulta, le abrimos la puerta
        return $next($request);
    }
}