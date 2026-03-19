<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Asegúrate de que esta ruta sea idéntica a la ubicación real de tu controlador
use App\Http\Controllers\AlumnoController; 

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
