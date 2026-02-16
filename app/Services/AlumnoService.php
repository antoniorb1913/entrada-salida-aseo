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

    public function getAllAlum() {
        return Alumno::all();
    }
}
