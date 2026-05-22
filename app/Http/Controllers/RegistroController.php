<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegistroService;
use Illuminate\Support\Facades\Auth;
use App\Exports\RegistrosExport;
use App\Http\Requests\RegistroRequest;
use Maatwebsite\Excel\Facades\Excel;
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

    // ... dentro de la clase RegistroController ...

    public function exportar(RegistroRequest $request) 
    {
        // 1. Obtenemos el objeto (que viene paginado con 15 registros por tu Service)
        $paginador = $this->registroService->buscarRegistros($request);
    
        // 2. Extraemos la colección de datos (los 15 registros)
        $registros = $paginador->getCollection(); 
    
        // 3. Nombre del archivo
        $nombreArchivo = 'reporte_salidas_' . now()->format('d-m-Y') . '.xlsx';
    
        // 4. Se lo pasamos al Exportador (Excel ahora recibirá la lista limpia)
        return Excel::download(new RegistrosExport($registros), $nombreArchivo);
    }

    public function dashboard()
    {
        // Le pedimos el aforo al servicio de registros
        $aforo = $this->registroService->obtenerAlumnosFuera();

        // Cargamos la vista 'admin' pasándole el aforo
        return view('admin', compact('aforo'));
    }

}