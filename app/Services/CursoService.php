<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Curso;

class CursoService
{
    /**
     * MÉTODO: OBTENER ETAPAS ÚNICAS
     * ¿Qué hace?: Busca en la tabla de cursos y saca las etapas que existen (ESO, Bachillerato, FP...).
     * Usa 'distinct()' para que si hay 40 cursos de la ESO, la palabra "ESO" aparezca una sola vez.
     */
    public function getEtapasUnicas() {
        return Curso::distinct()->pluck('etapas');
    }
    
    /**
     * MÉTODO: OBTENER MODALIDADES POR ETAPA
     * ¿Qué hace?: Al elegir una etapa (ej: Bachillerato), busca qué ramas o modalidades tiene asociadas 
     * (como Ciencias, Humanidades, etc.) sin repetir nombres.
     * ¿Para qué sirve?: Para crear los botones de la segunda pantalla del flujo de selección.
     */
    public function getModalidadesPorEtapa($etapa) {
        return Curso::where('etapas', $etapa)
                    ->distinct()
                    ->pluck('modalidad');
    }
    
    /**
     * MÉTODO: OBTENER NIVELES POR ETAPA
     * ¿Qué hace?: Busca los niveles disponibles (1º, 2º, 3º...). Tiene un ajuste: si la modalidad 
     * es "común" (como pasa en la ESO, donde no hay ramas), busca los registros que tienen ese campo vacío (`whereNull`) 
     * para evitar errores de cruce de datos.
     */
    public function getNivelesPorEtapa($etapa, $modalidad) {
        $query = Curso::where('etapas', $etapa);
        
        // Ajuste: Para la ESO (comun), filtramos explícitamente por null
        if ($modalidad === 'comun' || is_null($modalidad)) {
            $query->whereNull('modalidad');
        } else {
            $query->where('modalidad', $modalidad);
        }
        
        return $query->distinct()->pluck('nivel');
    }

    /**
     * MÉTODO: OBTENER LETRAS POR NIVEL
     * ¿Qué hace?: Con la etapa, la modalidad y el nivel ya elegidos, busca qué letras o grupos existen en el centro (A, B, C...).
     */
    public function getLetrasPorNivel($etapa, $modalidad, $nivel) {
        $query = Curso::where('etapas', $etapa)
                      ->where('nivel', $nivel);

        if ($modalidad === 'comun' || is_null($modalidad)) {
            $query->whereNull('modalidad');
        } else {
            $query->where('modalidad', $modalidad);
        }

        return $query->get(['id', 'letra']); 
    }

    /**
     * MÉTODO: OBTENER CURSO POR ID
     * ¿Qué hace?: Busca toda la información de un curso concreto usando su ID. Si por algún motivo raro el ID no existe, 
     * usa `findOrFail` para lanzar un error controlado en vez de romper la web.
     * ¿Para qué sirve?: Para saber los datos del grupo actual y poder pintar el título en la pantalla (ej: "Alumnos de 2º Bachillerato A").
     */
    public function getCursoPorId($curso_id) {
        return Curso::findOrFail($curso_id);
    }

    /**
     * MÉTODO: OBTENER ALUMNOS POR CURSO
     * ¿Qué hace?: Se va a la tabla de alumnos, extrae a todos los que pertenecen al ID del curso seleccionado y los ordena 
     * alfabéticamente por sus apellidos.
     */
    public function getAlumnosPorCurso($curso_id) {
        return Alumno::where('curso_id', $curso_id)
                    ->orderBy('apellidos')
                    ->get();
    }
}