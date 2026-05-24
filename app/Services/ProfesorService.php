<?php

namespace App\Services;

use App\Models\User;

class ProfesorService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * MÉTODO: OBTENER TODOS LOS PROFESORES
     * ¿Qué hace?: Va a la tabla de usuarios (`User`), que es donde están guardados los profesores,
     * los organiza por orden alfabético de la A a la Z según su nombre y se los trae todos juntos.
     */
    public function getAllProf() {
        $profesores = User::orderBy('nombre', 'asc')->get(); 
        return $profesores;
    }
}