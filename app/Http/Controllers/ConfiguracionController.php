<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ConfiguracionService;
use App\Models\Configuracion;
use App\Models\Alumno;

class ConfiguracionController extends Controller
{
    protected $configuracionService;

    public function __construct(ConfiguracionService $configuracionService)
    {
        $this->configuracionService = $configuracionService;
    }

    public function index()
    {
        // 1. Usamos el nuevo método centralizado del Modelo
        $config = Configuracion::todas();
        
        // 2. Pasamos a minutos solo para la vista (la lógica de conversión vive aquí o en el Service)
        $maxSalidas = $config->max_salidas;
        $tiempoEsperaMinutos = $config->tiempo_espera / 60;

        $alumnos = Alumno::with('curso')->orderBy('curso_id')->orderBy('apellidos')->get();

        return view('configuracion', compact('maxSalidas', 'tiempoEsperaMinutos', 'alumnos'));
    }

    public function guardar(Request $request)
    {
        // El camarero (Controller) le pasa el pedido al cocinero (Service)
        
        // 1. Guardar límites (el Service se encargará de pasarlo a segundos)
        $this->configuracionService->guardarLimites(
            $request->max_salidas, 
            $request->tiempo_espera
        );

        // 2. Gestionar alumnos VIP
        $this->configuracionService->actualizarExcepciones(
            $request->excepciones ?? []
        );

        return redirect()->route('configuracion.index')->with('success', 'Configuración guardada correctamente.');
    }
}