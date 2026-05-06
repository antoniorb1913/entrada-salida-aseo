<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Alumno;
use Illuminate\Http\Request;
use App\Services\RegistroService;
use App\Models\Curso;
use App\Models\User;
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

    public function index() {
        return view('consultas-registros');
    }

    // --- TODOS APUNTAN A LA MISMA VISTA 'consultas-filtros' ---

    public function formFecha() {
        return view('filtros-consultas', ['tipo' => 'fecha']);
    }

    public function formGrupo() {
        $cursos = Curso::all();
        return view('filtros-consultas', [
            'cursos' => $cursos, 
            'tipo' => 'grupo'
        ]);
    }

    public function formProfesor() {
        $profesores = $this->profesorService->getAllProf(); 
        return view('filtros-consultas', [
            'profesores' => $profesores, 
            'tipo' => 'profesor'
        ]);
    }

    public function formAlumno() {
        $alumnos = $this->alumnoService->getAllAlum(); 
        return view('filtros-consultas', [
            'alumnos' => $alumnos, 
            'tipo' => 'alumno'
        ]);
    }

    public function resultados(RegistroRequest $request) 
    {
        $registros = $this->registroService->buscarRegistros($request);
        return view('tabla-resultados', compact('registros'));
    }
}