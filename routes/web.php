<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccesoController; 

// --- RUTAS PÚBLICAS ---
// Si el usuario no está logueado, Laravel lo mandará aquí por defecto
Route::view('/', "login")->name('login');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');

// --- ZONAS PROTEGIDAS (Requieren Login) ---
Route::middleware('auth')->group(function () {
    
    // 1. Dashboards según rol
    Route::view('/admin', "admin")->name('admin');
    Route::view('/profesor', "profesor")->name('profesor');
    Route::view('/consulta', "consulta")->name('consulta');

    // 2. Flujo de Selección para ir al Baño
    Route::prefix('acceso')->group(function () {
        // Paso 1: Ver Etapas (Muestra el archivo acceso-aseo.blade.php)
        Route::get('/', [AccesoController::class, 'index'])->name('acceso');
        
        // Paso 2: Ver Niveles (1, 2, 3...)
        Route::get('/niveles/{etapa}', [AccesoController::class, 'niveles'])->name('acceso.niveles');
        
        // Paso 3: Ver Letras (A, B, DAW...)
        Route::get('/letras/{etapa}/{nivel}', [AccesoController::class, 'letras'])->name('acceso.letras');
        
        // Paso 4: Ver Lista de Alumnos
        // Esta ruta ya la tienes, pero confírmala:
        Route::get('/alumnos/{curso_id}', [AccesoController::class, 'alumnos'])->name('acceso.alumnos');
    });

    // 4. Salida
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});