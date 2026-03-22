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
    // --- SISTEMA DE CONSULTAS Y FILTROS ---
    Route::prefix('registros')->group(function () {
        // Menú principal
        Route::get('/', [\App\Http\Controllers\ConsultaController::class, 'index'])->name('registros');

        // Formularios de filtro
        Route::get('/filtro-fecha', [\App\Http\Controllers\ConsultaController::class, 'formFecha'])->name('consulta.fecha');
        Route::get('/filtro-grupo', [\App\Http\Controllers\ConsultaController::class, 'formGrupo'])->name('consulta.grupo');
        Route::get('/filtro-profesor', [\App\Http\Controllers\ConsultaController::class, 'formProfesor'])->name('consulta.profesor');
        Route::get('/filtro-alumno', [\App\Http\Controllers\ConsultaController::class, 'formAlumno'])->name('consulta.alumno');

        // Resultados
        Route::get('/resultados', [\App\Http\Controllers\ConsultaController::class, 'resultados'])->name('registros.resultados');
    });
});

