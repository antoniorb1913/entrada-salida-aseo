<?php

namespace App\Services;

use App\Enums\Estado;
use App\Models\Alumno;
use App\Models\Registro;
use Carbon\Carbon;

class RegistroService
{
    public function registrar_salida_alumno($alumno_id, $profesor_id)
    {
        $alumno = Alumno::findOrFail($alumno_id);
        $hoy = now()->toDateString();
        
        // --- CONFIGURACIÓN FIJA (5 MINUTOS) ---
        $limiteSalidas = 3;
        $tiempoEsperaSegundos = 300; // 5 minutos exactos

        // 1. Límite de salidas diarias
        $salidasHoy = Registro::where('alumno_id', $alumno_id)
                            ->whereDate('fecha_salida', $hoy)
                            ->count();

        if ($salidasHoy >= $limiteSalidas) {
            return ['success' => false, 'error' => 'Límite de salidas alcanzado por hoy.'];
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
}