<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Curso;
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

    public function letras($etapa, $nivel)
    {
        // Obtenemos las letras (ej: A, B, C o ARTES, CIENCIA)
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $nivel);

        return view('acceso-letras', compact('letras', 'etapa', 'nivel'));
    }
    
    
    public function alumnos($curso_id)
    {
        // Llamamos al Service para obtener el curso y los alumnos
        $curso = $this->cursoService->getCursoPorId($curso_id);
        $alumnos = $this->cursoService->getAlumnosPorCurso($curso_id);

        // Pasamos los datos a tu blade acceso-alumnos
        return view('acceso-alumnos', compact('alumnos', 'curso'));
    }
}