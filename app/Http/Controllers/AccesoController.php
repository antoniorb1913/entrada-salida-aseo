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
        // --- LÓGICA PERSONALIZADA: ESO ---
        // Si el usuario elige ESO, saltamos el paso de modalidad y vamos a niveles
        if ($etapa === 'ESO') {
            return redirect()->route('acceso.niveles', [$etapa, 'comun']);
        }

        $modalidades = $this->cursoService->getModalidadesPorEtapa($etapa);
        
        // Comprobación de seguridad por si no hay modalidades registradas
        if ($modalidades->count() <= 1 && ($modalidades->first() == null || $modalidades->first() == 'comun')) {
            return redirect()->route('acceso.niveles', [$etapa, 'comun']);
        }
    
        return view('acceso-modalidades', compact('modalidades', 'etapa'));
    }
    
    public function niveles($etapa, $modalidad)
    {
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa, $modalidad);
        return view('acceso-niveles', compact('niveles', 'etapa', 'modalidad'));
    }

    public function letras($etapa, $modalidad, $nivel)
    {
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $modalidad, $nivel);

        // --- LÓGICA PERSONALIZADA: FP ---
        // En FP, la "letra" es el código del ciclo (DAW, SMR...). Como ya eligieron la 
        // modalidad antes, aquí solo habrá 1 resultado. Saltamos directo a los alumnos.
        if ($etapa === 'FP' && $letras->count() === 1) {
            return redirect()->route('acceso.alumnos', $letras->first()->id);
        }

        return view('acceso-letras', compact('letras', 'etapa', 'modalidad', 'nivel'));
    }
    
    public function alumnos($curso_id)
    {
        $curso = $this->cursoService->getCursoPorId($curso_id);
        $alumnos = $this->cursoService->getAlumnosPorCurso($curso_id);
        
        $tiempoEsperaSegundos = 300; 

        return view('acceso-alumnos', compact('alumnos', 'curso', 'tiempoEsperaSegundos'));
    }
}