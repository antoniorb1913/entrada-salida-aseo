<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use Illuminate\Http\Request;
use App\Services\RegistroService; // Importamos el servicio
use App\Models\Curso;
use App\Models\User;

class ConsultaController extends Controller
{
    protected $registroService;

    // Inyectamos el servicio en el constructor
    public function __construct(RegistroService $registroService)
    {
        $this->registroService = $registroService;
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
        $profesores = User::all();
        return view('form_profesor', compact('profesores'));
    }

    public function formAlumno() {
        return view('form_alumno');
    }

    public function resultados(RegistroRequest $request) 
    {
        // Toda la lógica de filtrado ahora vive en el Service
        $registros = $this->registroService->buscarRegistros($request);

        // Devolvemos la vista de la tabla
        return view('tabla-resultados', compact('registros'));
    }
}