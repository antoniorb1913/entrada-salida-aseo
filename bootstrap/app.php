<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Esta es la forma oficial de Laravel 11 para redirigir a los que no tienen sesión
        $middleware->redirectGuestsTo('http://localhost:8000');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Aquí no hace falta poner nada para la redirección, 
        // ya se encarga el middleware de arriba.
    })->create();