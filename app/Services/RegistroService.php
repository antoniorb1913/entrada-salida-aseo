<?php

namespace App\Services;

use App\Enums\Estado;
use App\Models\Alumno;
use App\Models\Configuracion;
use App\Models\Registro;
use Carbon\Carbon;
use Exception;

class RegistroService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function registrar_salida_alumno($alumno_id, $profesor_id)
    {
        $alumno = Alumno::findOrFail($alumno_id);
        $hoy = now()->toDateString();
        
        // Configuración de prueba (luego la traerás de la base de datos)
        $limiteSalidas = 15;
        $tiempoEsperaSegundos = 10; // 10 segundos de prueba

        // --- REGLA 1: Límite de salidas diarias por alumno ---
        $salidasHoy = Registro::where('alumno_id', $alumno_id)
                            ->whereDate('fecha_salida', $hoy)
                            ->count();
        if ($salidasHoy >= $limiteSalidas) {
            return ['success' => false, 'error' => "{$alumno->nombre} ya ha agotado sus {$limiteSalidas} salidas al baño por hoy."];
        }

        // --- REGLA 2: Salidas escalonadas (Se anula si ya volvió) ---
        // Buscamos a qué hora salió la ÚLTIMA persona de esta clase
        $ultimaSalidaClase = Registro::where('curso_id', $alumno->curso_id)
                                    ->whereDate('fecha_salida', $hoy)
                                    ->latest('fecha_salida')
                                    ->first();

        // AQUÍ ESTÁ EL TRUCO: Solo contamos el tiempo si esa última persona SIGUE FUERA.
        // Si ya volvió (estado EN_CLASE), ignoramos el tiempo de espera.
        if ($ultimaSalidaClase && $ultimaSalidaClase->estado === Estado::FUERA) {
            
            $segundosDesdeUltimaSalida = \Carbon\Carbon::parse($ultimaSalidaClase->fecha_salida)->diffInSeconds(now());
            
            if ($segundosDesdeUltimaSalida < $tiempoEsperaSegundos) {
                $faltan = $tiempoEsperaSegundos - $segundosDesdeUltimaSalida;
                return [
                    'success' => false, 
                    'error' => "Aún no puede salir otro alumno. Deben pasar {$tiempoEsperaSegundos} segundos entre salidas. Faltan {$faltan} segundos."
                ];
            }
        }

        // --- SI PASA LAS REGLAS, REGISTRAMOS ---
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
        // Buscamos el registro activo (el que no tiene fecha_entrada aún)
        $registro = Registro::where('alumno_id', $alumno_id)
                            ->where('estado', Estado::FUERA)
                            ->latest()
                            ->first();

        if ($registro) {
            $registro->update([
                'fecha_entrada' => now(), // Guarda fecha y hora de la vuelta
                'estado'        => Estado::EN_CLASE 
            ]);
        }
    }
}