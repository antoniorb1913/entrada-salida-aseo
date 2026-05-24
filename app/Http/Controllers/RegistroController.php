<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Services\RegistroService;
use Illuminate\Support\Facades\Auth;
use App\Exports\RegistrosExport;
use Maatwebsite\Excel\Facades\Excel;

class RegistroController extends Controller
{
    protected $registroService;

    public function __construct(RegistroService $registroService)
    {
        $this->registroService = $registroService;
    }

    /**
     * ACCIÓN 1: EL ALUMNO SALE AL ASEO
     * ¿Qué hace?: Se activa cuando el profesor le da al botón "Salida". Llama al servicio pasándole el ID del alumno
     * y el ID del profesor que está logueado en ese momento (`Auth::id()`). 
     * ¿Para qué sirve?: Guarda la hora exacta de salida. Si el alumno tiene un bloqueo (por ejemplo, porque ya ha salido
     * muchas veces hoy), el servicio avisa, frena la acción y muestra un mensaje rojo de error en pantalla. Si no, lo deja salir.
     */
    public function registrar_salida_alumno($alumno_id) 
    {
        // 1. Guardamos el resultado del servicio en una variable
        $resultado = $this->registroService->registrar_salida_alumno($alumno_id, Auth::id());

        // 2. Comprobamos si el servicio devolvió success = false (el alumno no puede salir)
        if (!$resultado['success']) {
            // Retornamos hacia atrás con el mensaje de error del servicio
            return back()->with('error', $resultado['error']);
        }

        // 3. Si todo fue bien, devolvemos el status normal
        return back()->with('status', 'Alumno en el baño.');
    }

    /**
     * ACCIÓN 2: EL ALUMNO VUELVE A CLASE
     * ¿Qué hace?: Se ejecuta cuando el profesor le da al botón "Volver". Le dice al servicio que el alumno ha regresado.
     * ¿Para qué sirve?: Guarda la hora de llegada, calcula los minutos que ha pasado en el baño y libera su tarjeta
     * para que la pantalla se vuelva a poner en verde.
     */
    public function registrar_entrada_alumno($alumno_id) 
    {
        $this->registroService->registrar_entrada_alumno($alumno_id);
        return back()->with('status', 'Alumno ha vuelto a clase.');
    }

    /**
     * ACCIÓN 3: EXPORTAR LOS DATOS A EXCEL
     * ¿Qué hace?: Recoge las búsquedas y filtros que ha puesto Dirección en el panel. Coge esa lista de datos filtrados,
     * le genera un nombre con la fecha del día actual (ej: reporte_salidas_24-05-2026.xlsx) y prepara la descarga.
     */
    public function exportar(RegistroRequest $request) 
    {
        $registros = $this->registroService->buscarRegistros($request, true); 
    
        // 2. Nombre del archivo dinámico con la fecha actual
        $nombreArchivo = 'reporte_salidas_' . now()->format('d-m-Y') . '.xlsx';
    
        // 3. Se lo pasamos al Exportador (Excel ahora recibirá la lista completa y limpia)
        return Excel::download(new RegistrosExport($registros), $nombreArchivo);
    }

    /**
     * ACCIÓN 4: DASHBOARD DE ADMINISTRACIÓN
     * ¿Qué hace?: Cuenta rápidamente cuántos alumnos de todo el centro están ahora mismo en el baño (el aforo activo).
     */
    public function dashboard()
    {
        // Le pedimos el aforo al servicio de registros
        $aforo = $this->registroService->obtenerAlumnosFuera();

        // Cargamos la vista 'admin' pasándole el aforo
        return view('admin', compact('aforo'));
    }
}