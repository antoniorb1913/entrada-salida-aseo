<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ConsultaController;

// Formulario de Login y Acción
Route::view('/', "login")->name('login');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');

// ZONAS PROTEGIDAS
Route::middleware('auth')->group(function () {
    
    // Dashboards principales
    Route::view('/admin', "admin")->name('admin');
    Route::view('/profesor', "profesor")->name('profesor');
    Route::view('/consulta', "consulta")->name('consulta');

    Route::get('/acceso-baño', [RegistroController::class, 'index'])->name('acceso');
    Route::get('/alumnos/modificar', [AlumnoController::class, 'index'])->name('modificar');
    Route::get('/historial', [ConsultaController::class, 'index'])->name('consultas');

    // Cerrar sesión
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});