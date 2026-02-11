<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class AccesoController extends Controller
{
    public function index() {
        $etapas = Curso::distinct()->pluck('etapas');
        return $etapas; // Esto detendrá la web y nos mostrará qué hay en la DB
    }
    public function niveles($etapa)
    {
        $niveles = Curso::where('etapas', $etapa)->distinct()->pluck('nivel');
        return view('acceso.niveles', compact('niveles', 'etapa'));
    }
}