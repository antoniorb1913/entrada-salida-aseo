<?php

namespace App\Services;

use App\Enums\Estado;
use App\Models\Alumno;
use App\Models\Registro;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Añadido para poder consultar la configuración

class RegistroService
{
    public function registrar_salida_alumno($alumno_id, $profesor_id)
    {
        $alumno = Alumno::findOrFail($alumno_id);
        $hoy = now()->toDateString();
        
        // --- CONFIGURACIÓN DINÁMICA (DESDE LA BASE DE DATOS) ---
        $limiteSalidas = DB::table('configuraciones')->where('clave', 'max_salidas')->value('valor') ?? 3;
        $tiempoEsperaSegundos = DB::table('configuraciones')->where('clave', 'tiempo_espera_segundos')->value('valor') ?? 300;

        // 1. Límite de salidas diarias
        $salidasHoy = Registro::where('alumno_id', $alumno_id)
                            ->whereDate('fecha_salida', $hoy)
                            ->count();

        // LA MAGIA: Comprobamos si choca con el límite Y además NO tiene excepción médica
        if ($salidasHoy >= $limiteSalidas && !$alumno->excepcion_limite) {
            return ['success' => false, 'error' => "Límite de salidas alcanzado por hoy ($limiteSalidas)."];
        }

        // 2. Salidas escalonadas (Solo si el anterior sigue fuera)
        $ultimaSalidaClase = Registro::where('curso_id', $alumno->curso_id)
                                    ->whereDate('fecha_salida', $hoy)
                                    ->latest('fecha_salida')
                                    ->first();

        if ($ultimaSalidaClase && $ultimaSalidaClase->estado === Estado::FUERA) {
            $segundosDesdeSalida = intval(Carbon::parse($ultimaSalidaClase->fecha_salida)->diffInSeconds(now()));
            
            if ($segundosDesdeSalida < $tiempoEsperaSegundos) {
                $faltan = $tiempoEsperaSegundos - $segundosDesdeSalida;
                $tiempoFormateado = gmdate('i:s', $faltan);

                return [
                    'success' => false, 
                    'error' => "Espera de seguridad: faltan {$tiempoFormateado} minutos para la siguiente salida."
                ];
            }
        }

        // 3. Registro de la salida
        Registro::create([
            'alumno_id'    => $alumno_id,
            'profesor_id'  => $profesor_id,
            'curso_id'     => $alumno->curso_id,
            'fecha_salida' => now(),
            'estado'       => Estado::FUERA 
        ]);

        return ['success' => true];
    }

    public function registrar_entrada_alumno($alumno_id)
    {
        $registro = Registro::where('alumno_id', $alumno_id)
                            ->where('estado', Estado::FUERA)
                            ->latest()
                            ->first();

        if ($registro) {
            $registro->update([
                'fecha_entrada' => now(),
                'estado'        => Estado::EN_CLASE 
            ]);
        }
    }

    public function buscarRegistros($request)
    {
        // Carga relaciones (Eager Loading) para evitar el problema N+1
        $query = Registro::with(['alumno', 'curso', 'profesor']);
    
        // 1. PRIORIDAD: Rango de fechas (incluye día único si inicio == fin)
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereDate('fecha_salida', '>=', $request->fecha_inicio)
                ->whereDate('fecha_salida', '<=', $request->fecha_fin);
        } 
        // 2. BACKUP: Por si acaso algún formulario viejo solo manda 'fecha'
        elseif ($request->filled('fecha')) {
            $query->whereDate('fecha_salida', $request->fecha);
        }
    
        // Filtro por Grupo/Curso
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }
    
        // Filtro por Profesor
        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }
    
        // Filtro por Alumno
        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
        }
    
        // Ordenar por la salida más reciente y paginar
        // Usamos appends para que al cambiar de página en la tabla se mantengan los filtros en la URL
        return $query->orderBy('fecha_salida', 'desc')->paginate(15);
    }
}