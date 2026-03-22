<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\Curso;
use App\Models\User; // Asegúrate de que este es el modelo de tus profesores

class ConsultaController extends Controller
{
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
        $profesores = User::all(); // O User::role('profesor')->get() si usas roles
        return view('form_profesor', compact('profesores'));
    }

    public function formAlumno() {
        return view('form_alumno');
    }

    public function resultados(Request $request) {
        // Si entran sin elegir ningún filtro, los devolvemos al menú
        if (!$request->hasAny(['fecha', 'curso_id', 'profesor_id', 'alumno_id'])) {
            return redirect()->route('consulta')->with('error', 'Por favor, selecciona al menos un filtro para buscar.');
        }

        // Preparamos la consulta (Eager Loading para que vaya muy rápido)
        $query = Registro::with(['alumno', 'curso', 'profesor']);

        // Aplicamos los filtros que hayan llegado
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_salida', $request->fecha);
        }
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }
        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }
        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
        }

        // Ordenamos por los más recientes y paginamos de 15 en 15
        $registros = $query->latest('fecha_salida')->paginate(15);

        return view('tabla-resultados', compact('registros'));
    }
}