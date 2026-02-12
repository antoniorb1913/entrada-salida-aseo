<?php

namespace App\Http\Controllers;

use App\Services\CursoService;
use Illuminate\Http\Request;

class AccesoController extends Controller
{
    protected $cursoService;

    // Inyectamos el servicio en el constructor
    public function __construct(CursoService $cursoService)
    {
        $this->cursoService = $cursoService;
    }

    public function index()
    {
        // El controlador ya no sabe CÓMO se obtienen los datos, solo los pide
        $etapas = $this->cursoService->getEtapasUnicas();

        return view('etapas', compact('etapas'));
    }

    public function niveles($etapa)
    {
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa);
        
    
        return view('acceso-niveles', compact('niveles', 'etapa'));
    }
}