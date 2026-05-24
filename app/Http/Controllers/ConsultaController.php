<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Services\RegistroService;
use App\Models\Curso;
use App\Services\AlumnoService;
use App\Services\ProfesorService;

class ConsultaController extends Controller
{
    protected $registroService;
    protected $alumnoService;
    protected $profesorService;

    public function __construct(RegistroService $registroService, AlumnoService $alumnoService, ProfesorService $profesorService)
    {
        $this->registroService = $registroService;
        $this->alumnoService = $alumnoService;
        $this->profesorService = $profesorService;
    }

    /**
     * PANTALLA PRINCIPAL DE CONSULTAS
     * ¿Qué hace?: Carga el menú principal de la zona de administración.
     */
    public function index() {
        return view('consultas-registros');
    }

    // --- NOTA: LOS SIGUIENTES 4 MÉTODOS MANDAN A LA MISMA VISTA ('filtros-consultas') PERO CAMBIANDO EL CONTENIDO ---

    /**
     * FORMULARIO BÚSQUEDA POR FECHA
     * ¿Qué hace?: Abre la pantalla de filtros configurada para el tipo "fecha".
     */
    public function formFecha() {
        return view('filtros-consultas', ['tipo' => 'fecha']);
    }

    /**
     * FORMULARIO BÚSQUEDA POR GRUPO (AULA)
     * ¿Qué hace?: Se trae todos los cursos del instituto (ej: 1º ESO A, 2º Bachillerato B) de la base de datos.
     */
    public function formGrupo() {
        $cursos = Curso::all();
        return view('filtros-consultas', [
            'cursos' => $cursos, 
            'tipo' => 'grupo'
        ]);
    }

    /**
     * FORMULARIO BÚSQUEDA POR PROFESOR
     * ¿Qué hace?: Le pide al ayudante (servicio) la lista de todos los profesores del centro.
     */
    public function formProfesor() {
        $profesores = $this->profesorService->getAllProf(); 
        return view('filtros-consultas', [
            'profesores' => $profesores, 
            'tipo' => 'profesor'
        ]);
    }

    /**
     * FORMULARIO BÚSQUEDA POR ALUMNO
     * ¿Qué hace?: Le pide al ayudante la lista completa de todos los alumnos matriculados.
     */
    public function formAlumno() {
        $alumnos = $this->alumnoService->getAllAlum(); 
        return view('filtros-consultas', [
            'alumnos' => $alumnos, 
            'tipo' => 'alumno'
        ]);
    }

    /**
     * PANTALLA DE RESULTADOS (La tabla final)
     * ¿Qué hace?: Se ejecuta cuando el usuario pulsa el botón "Ver Registros". El validador (RegistroRequest) 
     * comprueba que los filtros estén bien puestos, el servicio busca en la base de datos 
     * las salidas que coincidan y las manda a la pantalla.
     */
    public function resultados(RegistroRequest $request) 
    {
        $registros = $this->registroService->buscarRegistros($request);
        return view('tabla-resultados', compact('registros'));
    }
}