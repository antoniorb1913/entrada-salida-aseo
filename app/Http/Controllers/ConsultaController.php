<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Alumno;
use Illuminate\Http\Request;
use App\Services\RegistroService; // Importamos el servicio
use App\Models\Curso;
use App\Models\User;
use App\Services\AlumnoService;
use App\Services\ProfesorService;

class ConsultaController extends Controller
{
    protected $registroService;
    protected $alumnoService;
    protected $profesorService;

    // Inyectamos el servicio en el constructor
    public function __construct(RegistroService $registroService, AlumnoService $alumnoService, ProfesorService $profesorService)
    {
        $this->registroService = $registroService;
        $this->alumnoService = $alumnoService;
        $this->profesorService = $profesorService;
    }

    public function index() {
        return view('consultas-registros');
    }

    public function formFecha() {
        return view('form_fecha');
    }

    public function formGrupo() {
        $cursos = Curso::all();
        return view('form_grupo', compact('cursos'));
    }

    public function formProfesor() {
        $profesores = $alumnos = $this->profesorService->getAllProf(); 
        return view('form_profesor', compact('profesores'));
    }

    public function formAlumno() {
        $alumnos = $this->alumnoService->getAllAlum(); 
        return view('form_alumno', compact('alumnos'));
    }
    public function resultados(RegistroRequest $request) 
    {
        // Toda la lógica de filtrado ahora vive en el Service
        $registros = $this->registroService->buscarRegistros($request);

        // Devolvemos la vista de la tabla
        return view('tabla-resultados', compact('registros'));
    }
}