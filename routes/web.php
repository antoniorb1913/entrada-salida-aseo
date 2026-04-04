<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\RegistroController; 

// --- RUTAS PÚBLICAS ---
Route::view('/', "login")->name('login');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');

// --- ZONAS PROTEGIDAS (Requieren Login) ---
Route::middleware('auth')->group(function () {
    
    // 1. Dashboards según rol
    Route::view('/admin', "admin")->name('admin');
    
    // REDIRECCIÓN: El profesor se salta su página y va directo a los cursos
    Route::redirect('/profesor', '/acceso')->name('profesor');

    // 2. Flujo de Selección para ir al Baño
    Route::prefix('acceso')->group(function () {
        Route::get('/', [AccesoController::class, 'index'])->name('acceso');
        Route::get('/niveles/{etapa}', [AccesoController::class, 'niveles'])->name('acceso.niveles');
        Route::get('/letras/{etapa}/{nivel}', [AccesoController::class, 'letras'])->name('acceso.letras');
        Route::get('/alumnos/{curso_id}', [AccesoController::class, 'alumnos'])->name('acceso.alumnos');
    });

    // 3. Lógica de Registro (Salida y Entrada)
    Route::post('/registrar-salida/{alumno_id}', [RegistroController::class, 'registrar_salida_alumno'])->name('registro.salida');
    Route::post('/registrar-entrada/{alumno_id}', [RegistroController::class, 'registrar_entrada_alumno'])->name('registro.entrada');

    // 4. Salida de sesión
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    // ==========================================================
    // 5. ZONA EXCLUSIVA (BLOQUEADA PARA EL PROFESOR)
    // ==========================================================
    // ¡AQUÍ ESTÁ EL CAMBIO! Llamamos al middleware oficial
    Route::middleware(\App\Http\Middleware\SoloDireccion::class)->group(function () {
        
        // Vista de consulta protegida
        Route::view('/consulta', "consulta")->name('consulta');

        // --- SISTEMA DE CONSULTAS Y FILTROS ---
        Route::prefix('registros')->group(function () {
            Route::get('/', [ConsultaController::class, 'index'])->name('registros');
            Route::get('/filtro-fecha', [ConsultaController::class, 'formFecha'])->name('consulta.fecha');
            Route::get('/filtro-grupo', [ConsultaController::class, 'formGrupo'])->name('consulta.grupo');
            Route::get('/filtro-profesor', [ConsultaController::class, 'formProfesor'])->name('consulta.profesor');
            Route::get('/filtro-alumno', [ConsultaController::class, 'formAlumno'])->name('consulta.alumno');
            Route::get('/resultados', [ConsultaController::class, 'resultados'])->name('registros.resultados');
            Route::get('/registros/exportar', [RegistroController::class, 'exportar'])->name('consulta.exportar');
        });
    });
});