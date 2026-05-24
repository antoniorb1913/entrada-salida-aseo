<?php

namespace App\Services;

use App\Enums\Estado;
use App\Models\Alumno;
use App\Models\Registro;
use App\Models\Configuracion;
use Carbon\Carbon;

class RegistroService
{
    public function registrar_salida_alumno($alumno_id, $profesor_id)
    {
        $alumno = Alumno::findOrFail($alumno_id);
        $hoy = now()->toDateString();
        
        // --- CONFIGURACIÓN DINÁMICA CENTRALIZADA ---
        $config = Configuracion::todas();

        // 1. Límite de salidas diarias (Tu código original)
        $salidasHoy = Registro::where('alumno_id', $alumno_id)
                            ->whereDate('fecha_salida', $hoy)
                            ->count();

        if ($salidasHoy >= $config->max_salidas && !$alumno->excepcion_limite) {
            return [
                'success' => false, 
                'error' => "Límite de salidas alcanzado por hoy ({$config->max_salidas})."
            ];
        }

        // 2. Salidas escalonadas (Tu código original...)
        $ultimaSalidaClase = Registro::where('curso_id', $alumno->curso_id)
                                    ->whereDate('fecha_salida', $hoy)
                                    ->latest('fecha_salida')
                                    ->first();

        if ($ultimaSalidaClase && $ultimaSalidaClase->estado === Estado::FUERA) {
            $segundosDesdeSalida = intval(Carbon::parse($ultimaSalidaClase->fecha_salida)->diffInSeconds(now()));
            
            if ($segundosDesdeSalida < $config->tiempo_espera) {
                $faltan = $config->tiempo_espera - $segundosDesdeSalida;
                $tiempoFormateado = gmdate('i:s', $faltan);

                return [
                    'success' => false, 
                    'error' => "Espera de seguridad: faltan {$tiempoFormateado} minutos para la siguiente salida."
                ];
            }
        }

        // 3. Registro de la salida si todo lo anterior pasa con éxito
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

    /**
     * MÉTODO DE BÚSQUEDA CON FILTROS
     * ¿Qué hace?: Recoge todos los filtros que ha puesto Dirección (fechas, curso, profesor o alumno).
     * Construye la consulta para Postgres y, gracias al nuevo parámetro '$paraExportar', decide si devuelve
     * los datos partidos de 15 en 15 (para que la web cargue rápido) o el listado completo (para el Excel).
     */
    public function buscarRegistros($request, $paraExportar = false) // <-- Añadimos este "chivato" con valor falso por defecto
    {
        // Carga relaciones (Eager Loading) para evitar el problema de consultas lentas N+1
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
    
        $query->orderBy('fecha_salida', 'desc');

        // --- EL TRUCO INTELIGENTE ---
        // Si el controlador nos avisa de que es para el Excel, saltamos la paginación y devolvemos TODO (.get())
        if ($paraExportar) {
            return $query->get();
        }
    
        // Si es para la pantalla web normal, devolvemos solo 15 registros por página
        return $query->paginate(15);
    }

    /**
     * Obtiene el número total de alumnos actualmente fuera del aula.
     */
    public function obtenerAlumnosFuera(): object
    {
        // Contamos cuántos registros tienen el estado FUERA
        $totalFuera = Registro::where('estado', Estado::FUERA)->count();

        // 🌟 RETORNO MODIFICADO: Devolvemos únicamente el total real de manera directa
        return (object) [
            'total' => $totalFuera
        ];
    }
}