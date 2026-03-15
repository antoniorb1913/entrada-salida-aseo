<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegistroService;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    protected $registroService;

    // Inyectamos el servicio en el constructor
    public function __construct(RegistroService $registroService)
    {
        $this->registroService = $registroService;
    }

    public function registrar_salida_alumno($alumno_id) // Para la salida
    {
        $this->registroService->registrar_salida_alumno($alumno_id, Auth::id());
        return back()->with('status', 'Alumno en el baño.');
    }

    public function registrar_entrada_alumno($alumno_id) // Para la entrada
    {
        $this->registroService->registrar_entrada_alumno($alumno_id);
        return back()->with('status', 'Alumno ha vuelto a clase.');
    }
}
