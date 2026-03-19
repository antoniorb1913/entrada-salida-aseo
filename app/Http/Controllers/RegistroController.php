<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegistroService;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    protected $registroService;

    public function __construct(RegistroService $registroService)
    {
        $this->registroService = $registroService;
    }

    public function registrar_salida_alumno($alumno_id) 
    {
        // 1. Guardamos el resultado del servicio en una variable
        $resultado = $this->registroService->registrar_salida_alumno($alumno_id, Auth::id());

        // 2. Comprobamos si el servicio devolvió success = false
        if (!$resultado['success']) {
            // Retornamos hacia atrás con el mensaje de error del servicio
            return back()->with('error', $resultado['error']);
        }

        // 3. Si todo fue bien, devolvemos el status normal
        return back()->with('status', 'Alumno en el baño.');
    }

    public function registrar_entrada_alumno($alumno_id) 
    {
        $this->registroService->registrar_entrada_alumno($alumno_id);
        return back()->with('status', 'Alumno ha vuelto a clase.');
    }
}