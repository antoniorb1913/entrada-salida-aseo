<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Curso;

class CursoService
{
    public function getEtapasUnicas() {
        return Curso::distinct()->pluck('etapas');
    }
    
    // Asegúrate de que este nombre sea exacto: getNivelesPorEtapa
    public function getNivelesPorEtapa($etapa) {
        return Curso::where('etapas', strtoupper($etapa))
                    ->distinct()
                    ->pluck('nivel');
    }
    public function getLetrasPorNivel($etapa, $nivel) {
        // Cambiamos pluck() por get() para traer el ID y la LETRA
        return Curso::where('etapas', strtoupper($etapa))
                    ->where('nivel', $nivel)
                    ->get(['id', 'letra']); 
    }
    public function getCursoPorId($curso_id) {
        // Buscamos los datos del curso para el encabezado de la vista
        return Curso::findOrFail($curso_id);
    }

    public function getAlumnosPorCurso($curso_id) {
        // Buscamos los alumnos que pertenecen a este ID de curso
        return Alumno::where('curso_id', $curso_id)
                    ->orderBy('apellidos')
                    ->get();
    }
}