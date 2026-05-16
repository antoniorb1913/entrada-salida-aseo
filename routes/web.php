<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\SoloDireccion;

// --- RUTAS PÚBLICAS ---
// Ahora pasa por el controlador para verificar si ya hay sesión iniciada
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');

// --- ZONAS PROTEGIDAS (Requieren Login) ---
Route::middleware('auth')->group(function () {
    
    // REDIRECCIÓN: El profesor se salta su página y va directo a los cursos
    Route::redirect('/profesor', '/acceso')->name('profesor');

    // 1. Flujo de Selección para ir al Baño
    Route::prefix('acceso')->group(function () {
        Route::get('/', [AccesoController::class, 'index'])->name('acceso');
        Route::get('/modalidades/{etapa}', [AccesoController::class, 'modalidades'])->name('acceso.modalidades');
        Route::get('/acceso/niveles/{etapa}/{modalidad}', [AccesoController::class, 'niveles'])->name('acceso.niveles');
        Route::get('/letras/{etapa}/{modalidad}/{nivel}', [AccesoController::class, 'letras'])->name('acceso.letras');
        Route::get('/alumnos/{curso_id}', [AccesoController::class, 'alumnos'])->name('acceso.alumnos');
    });

    // 2. Lógica de Registro (Salida y Entrada)
    Route::post('/registrar-salida/{alumno_id}', [RegistroController::class, 'registrar_salida_alumno'])->name('registro.salida');
    Route::post('/registrar-entrada/{alumno_id}', [RegistroController::class, 'registrar_entrada_alumno'])->name('registro.entrada');

    // 3. Salida de sesión
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


    // ==========================================================
    // 4. ZONA EXCLUSIVA DIRECCIÓN (BLOQUEADA PARA EL PROFESOR)
    // ==========================================================
    Route::middleware(SoloDireccion::class)->group(function () {
        
        // Dashboard de Admin protegido (antes estaba fuera de este grupo)
        Route::view('/admin', "admin")->name('admin');

        // --- SISTEMA DE CONSULTAS Y FILTROS ---
        Route::prefix('registros')->group(function () {
            Route::get('/', [ConsultaController::class, 'index'])->name('registros');
            Route::get('/filtro-fecha', [ConsultaController::class, 'formFecha'])->name('consulta.fecha');
            Route::get('/filtro-grupo', [ConsultaController::class, 'formGrupo'])->name('consulta.grupo');
            Route::get('/filtro-profesor', [ConsultaController::class, 'formProfesor'])->name('consulta.profesor');
            Route::get('/filtro-alumno', [ConsultaController::class, 'formAlumno'])->name('consulta.alumno');
            Route::get('/resultados', [ConsultaController::class, 'resultados'])->name('registros.resultados');
            Route::get('/registros/exportar', [RegistroController::class, 'exportar'])->name('consulta.exportar');

            // --- Panel de configuración ---
            Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
            Route::post('/configuracion', [ConfiguracionController::class, 'guardar'])->name('configuracion.guardar');
        });
    });
});