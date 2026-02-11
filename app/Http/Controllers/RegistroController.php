<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso; // Asegúrate de importar tu modelo de Cursos

class RegistroController extends Controller
{
    /**
     * Esta es la función que te falta y que Laravel está reclamando
     */
    public function index()
    {
        // Obtenemos las etapas para la primera pantalla de selección
        $etapas = Curso::distinct()->pluck('etapas'); 

        // Retornamos la vista (asegúrate de que el nombre coincida con tu archivo .blade)
        return view('registros.acceso-aseo', compact('etapas'));
    }
}
