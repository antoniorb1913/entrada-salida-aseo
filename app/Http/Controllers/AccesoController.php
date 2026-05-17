<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\CursoService;
use App\Services\RegistroService; // <--- 1. IMPORTANTE: Importamos tu servicio de registros
use Illuminate\Http\Request;

class AccesoController extends Controller
{
    protected $cursoService;
    protected $registroService;

    // 3. Inyectamos ambos servicios en el constructor
    public function __construct(CursoService $cursoService, RegistroService $registroService)
    {
        $this->cursoService = $cursoService;
        $this->registroService = $registroService; // <--- Guardamos la instancia
    }

    public function index()
    {
        $etapas = $this->cursoService->getEtapasUnicas();
        
        $aforo = $this->registroService->obtenerAlumnosFuera();

        return view('etapas', compact('etapas', 'aforo'));
    }

    public function modalidades($etapa)
    {
        if ($etapa === 'ESO') {
            return $this->niveles($etapa, 'comun'); 
        }

        $modalidades = $this->cursoService->getModalidadesPorEtapa($etapa);
        
        if ($modalidades->count() <= 1 && ($modalidades->first() == null || $modalidades->first() == 'comun')) {
            return $this->niveles($etapa, 'comun');
        }
    
        return view('acceso-modalidades', compact('modalidades', 'etapa'));
    }
    
    public function niveles($etapa, $modalidad)
    {
        $niveles = $this->cursoService->getNivelesPorEtapa($etapa, $modalidad);
    
        if ($etapa === 'FP') {
            $curso = Curso::where('etapas', 'FP')
                        ->where('modalidad', $modalidad)
                        ->whereNull('letra') 
                        ->first();
        }
    
        return view('acceso-niveles', compact('niveles', 'etapa', 'modalidad'));
    }

    public function letras($etapa, $modalidad, $nivel)
    {
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $modalidad, $nivel);

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

        // 5. NUEVO: Le pedimos el aforo al RegistroService para la pantalla de tarjetas
        $aforo = $this->registroService->obtenerAlumnosFuera();

        return view('acceso-alumnos', compact('alumnos', 'curso', 'tiempoEsperaSegundos', 'aforo')); // <--- Pasamos $aforo
    }
}