<?php

namespace App\Http\Controllers;

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
        if ($etapa === 'ESO') {
            return $this->niveles($etapa, 'comun'); // Carga niveles directamente
        }

        $modalidades = $this->cursoService->getModalidadesPorEtapa($etapa);
        
        if ($modalidades->count() <= 1 && ($modalidades->first() == null || $modalidades->first() == 'comun')) {
            return $this->niveles($etapa, 'comun');
        }
    
        return view('acceso-modalidades', compact('modalidades', 'etapa'));
    }
    
    // Verifica que reciba $etapa y $modalidad
    public function niveles($etapa, $modalidad)
    {
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa, $modalidad);
    
        // Lógica para FP: Si solo hay un curso para este nivel (porque no hay letras)
        // saltamos directamente a la vista de alumnos.
        if ($etapa === 'FP') {
            // Buscamos si existe un curso único para ese ciclo y nivel (asumiendo que letra es null)
            // Si tu Service tiene un método para esto, úsalo. Si no, algo así:
            $curso = Curso::where('etapas', 'FP')
                        ->where('modalidad', $modalidad)
                        ->whereNull('letra') 
                        ->first(); // Esto asume que el ID se gestionará después o en la vista de niveles
        }
    
        return view('acceso-niveles', compact('niveles', 'etapa', 'modalidad'));
    }

    public function letras($etapa, $modalidad, $nivel)
    {
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $modalidad, $nivel);

        // --- CAMBIO PARA FP ---
        // En lugar de redirect, llamamos al método alumnos() directamente
        if ($etapa === 'FP' && $letras->count() === 1) {
            return $this->alumnos($letras->first()->id);
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