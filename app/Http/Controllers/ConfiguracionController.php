<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConfiguracionService;
use App\Models\Configuracion;
use App\Models\Alumno;
use App\Http\Requests\ConfiguracionRequest;

class ConfiguracionController extends Controller
{
    protected $configuracionService;

    public function __construct(ConfiguracionService $configuracionService)
    {
        $this->configuracionService = $configuracionService;
    }

    public function index()
    {
        // 1. Usamos el método centralizado del Modelo
        $config = Configuracion::todas();
        
        // 2. Extraemos valores y preparamos para la vista
        $maxSalidas = $config->max_salidas;
        $tiempoEsperaMinutos = $config->tiempo_espera / 60; // Conversión a minutos para el usuario
        $tiempoCancelacion = $config->tiempo_cancelacion; // Nuevo parámetro

        $alumnos = Alumno::with('curso')->orderBy('curso_id')->orderBy('apellidos')->get();

        return view('configuracion', compact('maxSalidas', 'tiempoEsperaMinutos', 'tiempoCancelacion', 'alumnos'));
    }

    public function guardar(ConfiguracionRequest $request)
    {
        // El camarero le pasa los 3 datos al cocinero (Service)
        $this->configuracionService->guardarLimites(
            $request->max_salidas, 
            $request->tiempo_espera,
            $request->tiempo_cancelacion // Pasamos el nuevo valor
        );

        $this->configuracionService->actualizarExcepciones(
            $request->excepciones ?? []
        );

        return redirect()->route('configuracion.index')->with('success', 'Configuración guardada correctamente.');
    }
}