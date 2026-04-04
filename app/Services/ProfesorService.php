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
    public function getAllProf() {
        $alumnos = User::orderBy('nombre', 'asc')->get(); 
        return $alumnos;
    }
}
