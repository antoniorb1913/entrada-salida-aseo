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
        $profesores = User::orderBy('nombre', 'asc')->get(); 
        return $profesores;
    }
}
