<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\RegistroController; 

// --- RUTAS PÚBLICAS ---
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
        Route::get('/', [AccesoController::class, 'index'])->name('acceso');
        Route::get('/niveles/{etapa}', [AccesoController::class, 'niveles'])->name('acceso.niveles');
        Route::get('/letras/{etapa}/{nivel}', [AccesoController::class, 'letras'])->name('acceso.letras');
        Route::get('/alumnos/{curso_id}', [AccesoController::class, 'alumnos'])->name('acceso.alumnos');
    });

    // 3. Lógica de Registro (Salida y Entrada)
    // Registro de Salida (Crea el registro inicial)
    Route::post('/registrar-salida/{alumno_id}', [RegistroController::class, 'registrar_salida_alumno'])->name('registro.salida');
    
    // Registro de Entrada (Cierra el registro existente)
    Route::post('/registrar-entrada/{alumno_id}', [RegistroController::class, 'registrar_entrada_alumno'])->name('registro.entrada');

    // 4. Salida de sesión
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});