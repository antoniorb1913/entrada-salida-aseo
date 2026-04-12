<?php

namespace App\Services;

use App\Models\Configuracion;
use App\Models\Alumno;

class ConfiguracionService
{
    /**
     * Procesa y guarda los límites numéricos de la aplicación.
     */
    public function guardarLimites($maxSalidas, $tiempoEsperaMinutos)
    {
        // El servicio se encarga de la lógica matemática (pasar minutos a segundos)
        $tiempoEsperaSegundos = $tiempoEsperaMinutos * 60;

        Configuracion::updateOrCreate(
            ['clave' => 'max_salidas'],
            ['valor' => $maxSalidas]
        );

        Configuracion::updateOrCreate(
            ['clave' => 'tiempo_espera_segundos'],
            ['valor' => $tiempoEsperaSegundos]
        );
    }

    /**
     * Resetea y asigna los permisos VIP a los alumnos indicados.
     */
    public function actualizarExcepciones($excepcionesIds = [])
    {
        // 1. Reiniciamos todas las excepciones a falso por seguridad
        Alumno::query()->update(['excepcion_limite' => false]);

        // 2. Aplicamos la excepción solo a los IDs recibidos
        if (!empty($excepcionesIds)) {
            Alumno::whereIn('id', $excepcionesIds)->update(['excepcion_limite' => true]);
        }
    }
}