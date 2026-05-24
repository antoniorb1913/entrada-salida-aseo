<?php

namespace App\Services;

use App\Models\Alumno;

class AlumnoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * MÉTODO: OBTENER TODOS LOS ALUMNOS
     * ¿Qué hace?: Se conecta a la tabla de alumnos, los organiza por orden alfabético 
     * de la A a la Z según su nombre y extrae la lista completa de la base de datos.
     */
    public function getAllAlum() {
        $alumnos = Alumno::orderBy('nombre', 'asc')->get(); 
        return $alumnos;
    }
}