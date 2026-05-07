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

    public function modalidades($etapa)
    {
        // Buscamos modalidades únicas para esa etapa
        $modalidades = $this->cursoService->getModalidadesPorEtapa($etapa);
        
        // Si no hay modalidades (es null), saltamos directo a niveles
        if ($modalidades->count() <= 1 && $modalidades->first() == null) {
            return redirect()->route('acceso.niveles', [$etapa, 'comun']);
        }
    
        return view('acceso-modalidades', compact('modalidades', 'etapa'));
    }
    
    public function niveles($etapa, $modalidad)
    {
        // Pasamos la modalidad para filtrar
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa, $modalidad);
        return view('acceso-niveles', compact('niveles', 'etapa', 'modalidad'));
    }

    public function letras($etapa, $modalidad, $nivel)
    {
        // Pasamos los 3 parámetros al service para obtener las letras correctas
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $modalidad, $nivel);

        // CRÍTICO: Añadir 'modalidad' al compact para que la vista la reciba
        return view('acceso-letras', compact('letras', 'etapa', 'modalidad', 'nivel'));
    }
    

    public function alumnos($curso_id)
    {
        $curso = $this->cursoService->getCursoPorId($curso_id);
        $alumnos = $this->cursoService->getAlumnosPorCurso($curso_id);
        
        $tiempoEsperaSegundos = 300; 

        // Al pasar $curso, ya llevamos la modalidad dentro del objeto
        return view('acceso-alumnos', compact('alumnos', 'curso', 'tiempoEsperaSegundos'));
    }
}