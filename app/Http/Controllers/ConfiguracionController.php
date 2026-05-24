<?php

namespace App\Http\Controllers;

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

    /**
     * MÉTODO INDEX (Cargar la pantalla de Ajustes)
     * ¿Qué hace?: Recoge de la base de datos las normas actuales (salidas máximas, tiempos de espera, etc.)
     * y la lista de todos los alumnos ordenada por apellidos y cursos.
     */
    public function index()
    {
        $config = Configuracion::todas();
        
        $maxSalidas = $config->max_salidas;
        $tiempoEsperaMinutos = $config->tiempo_espera / 60; // Pasa los segundos a minutos para que se entienda bien
        $tiempoCancelacion = $config->tiempo_cancelacion;

        $alumnos = Alumno::with('curso')->orderBy('curso_id')->orderBy('apellidos')->get();

        return view('configuracion', compact('maxSalidas', 'tiempoEsperaMinutos', 'tiempoCancelacion', 'alumnos'));
    }

    /**
     * MÉTODO GUARDAR (Procesar el formulario de cambios)
     * ¿Qué hace?: Se activa cuando el administrador le da al botón "Guardar Configuración".
     * Recoge los números que se han puesto en el formulario y actualiza tanto los límites de tiempo
     * globales como la lista de alumnos con "excepciones médicos" (los que pueden salir más veces).
     */
    public function guardar(ConfiguracionRequest $request)
    {
        // Guarda los límites de salidas y tiempos
        $this->configuracionService->guardarLimites(
            $request->max_salidas, 
            $request->tiempo_espera,
            $request->tiempo_cancelacion
        );

        // Guarda la lista de alumnos exceptuados (enfermedades, etc.)
        $this->configuracionService->actualizarExcepciones(
            $request->excepciones ?? []
        );

        return redirect()->route('configuracion.index')->with('success', 'Configuración guardada correctamente.');
    }
}