<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\Request;

class AccesoController extends Controller
{
    protected $cursoService;

    public function __construct(CursoService $cursoService)
    {
        $this->cursoService = $cursoService;
    }

    public function index()
    {
        $etapas = $this->cursoService->getEtapasUnicas();
        return view('etapas', compact('etapas'));
    }

    public function niveles($etapa)
    {
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa);
        return view('acceso-niveles', compact('niveles', 'etapa'));
    }

    public function letras($etapa, $nivel)
    {
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $nivel);
        return view('acceso-letras', compact('letras', 'etapa', 'nivel'));
    }
    
    public function alumnos($curso_id)
    {
        $curso = $this->cursoService->getCursoPorId($curso_id);
        $alumnos = $this->cursoService->getAlumnosPorCurso($curso_id);

        // --- DEFINIMOS EL TIEMPO FIJO AQUÍ (5 MINUTOS) ---
        // 5 minutos * 60 segundos = 300
        $tiempoEsperaSegundos = 300; 

        // Añadimos 'tiempoEsperaSegundos' al compact
        return view('acceso-alumnos', compact('alumnos', 'curso', 'tiempoEsperaSegundos'));
    }
}