<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\CursoService;
use App\Services\RegistroService;

class AccesoController extends Controller
{
    protected $cursoService;
    protected $registroService;

    public function __construct(CursoService $cursoService, RegistroService $registroService)
    {
        $this->cursoService = $cursoService;
        $this->registroService = $registroService;
    }

    /**
     * PANTALLA 1: MUESTRA LAS ETAPAS (ESO, Bachillerato, FP...)
     * ¿Qué hace?: Carga la primera página del flujo. Coge las etapas que existen 
     * en el centro y mira cuántos alumnos hay en el baño en ese momento (el aforo global).
     */
    public function index()
    {
        $etapas = $this->cursoService->getEtapasUnicas();
        $aforo = $this->registroService->obtenerAlumnosFuera();

        return view('etapas', compact('etapas', 'aforo'));
    }

    /**
     * PANTALLA 2: MUESTRA LAS MODALIDADES (Ciencias, Humanidades, etc.)
     * ¿Qué hace?: Al elegir la etapa, mira qué modalidades tiene. Si es la ESO (que no tiene 
     * modalidades porque todos dan lo mismo), salta automáticamente a la siguiente pantalla ('comun').
     */
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
    
    /**
     * PANTALLA 3: MUESTRA LOS NIVELES (1º, 2º, 3º...)
     * ¿Qué hace?: Recoge los niveles disponibles para la etapa y modalidad seleccionadas.
     */
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

    /**
     * PANTALLA 4: MUESTRA LAS LETRAS (A, B, C...)
     * ¿Qué hace?: Saca los grupos o letras que existen para ese año concreto. Si es FP y solo 
     * hay un único grupo sin letras, se salta este paso y va directo a los alumnos.
     */
    public function letras($etapa, $modalidad, $nivel)
    {
        $letras = $this->cursoService->getLetrasPorNivel($etapa, $modalidad, $nivel);

        if ($etapa === 'FP' && $letras->count() === 1) {
            return $this->alumnos($letras->first()->id);
        }

        return view('acceso-letras', compact('letras', 'etapa', 'modalidad', 'nivel'));
    }
    
    /**
     * PANTALLA 5 (FINAL): MUESTRA EL LISTADO DE ALUMNOS
     * ¿Qué hace?: Carga la lista con los nombres de todos los alumnos que pertenecen al grupo 
     * seleccionado. También calcula el tiempo de espera por defecto y comprueba el aforo del centro.
     */
    public function alumnos($curso_id)
    {
        $curso = $this->cursoService->getCursoPorId($curso_id);
        $alumnos = $this->cursoService->getAlumnosPorCurso($curso_id);
        
        $tiempoEsperaSegundos = 300; // 5 minutos por defecto

        $aforo = $this->registroService->obtenerAlumnosFuera();

        return view('acceso-alumnos', compact('alumnos', 'curso', 'tiempoEsperaSegundos', 'aforo'));
    }
}