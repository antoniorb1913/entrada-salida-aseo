<?php

namespace App\Services;

use App\Models\Configuracion;
use App\Models\Alumno;

class ConfiguracionService
{
    /**
     * MÉTODO GUARDAR LÍMITES (La lógica de los números y estado de los aseos)
     * ¿Qué hace?: Recoge los datos de configuración del formulario. Pasa los 
     * minutos a segundos y luego usa el comando `updateOrCreate` de Laravel (que significa: "si la clave ya existe en 
     * Postgres actualízala, y si no existe, créala desde cero").
     */
    public function guardarLimites($maxSalidas, $tiempoEsperaMinutos, $tiempoCancelacion, $aseoHombres, $aseoMujeres)
    {
        // El servicio hace el cálculo para guardar el tiempo en segundos dentro de la base de datos
        $tiempoEsperaSegundos = $tiempoEsperaMinutos * 60;

        // Guarda o actualiza el tope de salidas diarias
        Configuracion::updateOrCreate(
            ['clave' => 'max_salidas'],
            ['valor' => $maxSalidas]
        );
        
        // Guarda o actualiza los segundos máximos que puede estar un alumno fuera
        Configuracion::updateOrCreate(
            ['clave' => 'tiempo_espera_segundos'],
            ['valor' => $tiempoEsperaSegundos]
        );

        // Guarda o actualiza el tiempo límite para cancelar un marcaje erróneo
        Configuracion::updateOrCreate(
            ['clave' => 'tiempo_cancelacion'],
            ['valor' => $tiempoCancelacion]
        );

        // NUEVO: Guarda o actualiza el estado operativo del aseo de hombres
        Configuracion::updateOrCreate(
            ['clave' => 'aseo_hombres_disponible'],
            ['valor' => $aseoHombres]
        );

        // NUEVO: Guarda o actualiza el estado operativo del aseo de mujeres
        Configuracion::updateOrCreate(
            ['clave' => 'aseo_mujeres_disponible'],
            ['valor' => $aseoMujeres]
        );
    }

    /**
     * MÉTODO ACTUALIZAR EXCEPCIONES (Gestión de alumnos "VIP")
     * ¿Qué hace?: Primero, pone a todos los alumnos del instituto a "falso" en el campo de excepciones médica. 
     * Después, coge la lista de los IDs de los alumnos que el administrador ha marcado en la pantalla y a esos 
     * les cambia el campo a "verdadero" (`true`).
     */
    public function actualizarExcepciones($excepcionesIds = [])
    {
        // 1. Reseteamos a todos los alumnos a 'false' para limpiar lo que hubiera antes
        Alumno::query()->update(['excepcion_limite' => false]);

        // 2. Activamos la excepción ('true') solo a los alumnos elegidos en el formulario
        if (!empty($excepcionesIds)) {
            Alumno::whereIn('id', $excepcionesIds)->update(['excepcion_limite' => true]);
        }
    }

    /**
     * MÉTODO ACTUALIZAR TUTOR
     * ¿Qué hace?: Pone a todos los alumnos a "falso" en la necesidad de tutor y luego
     * activa en "verdadero" (`true`) únicamente a los seleccionados en el formulario.
     */
    public function actualizarTutor($necesita_tutor = [])
    {
        // 1. Reseteamos a todos los alumnos a 'false' para limpiar lo que hubiera antes
        Alumno::query()->update(['necesita_tutor' => false]);

        // 2. Activamos la necesidad de tutor ('true') solo a los alumnos elegidos en el formulario
        if (!empty($necesita_tutor)) {
            Alumno::whereIn('id', $necesita_tutor)->update(['necesita_tutor' => true]);
        }
    }
}