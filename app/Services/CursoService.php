<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Curso;

class CursoService
{
    public function getEtapasUnicas() {
        return Curso::distinct()->pluck('etapas');
    }
    
    public function getModalidadesPorEtapa($etapa) {
        return Curso::where('etapas', $etapa)
                    ->distinct()
                    ->pluck('modalidad');
    }
    
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

    public function getLetrasPorNivel($etapa, $modalidad, $nivel) {
        // Usamos la misma lógica de 'comun' que en niveles para ser consistentes
        $query = Curso::where('etapas', $etapa)
                      ->where('nivel', $nivel);

        if ($modalidad === 'comun' || is_null($modalidad)) {
            $query->whereNull('modalidad');
        } else {
            $query->where('modalidad', $modalidad);
        }

        return $query->get(['id', 'letra']); 
    }

    public function getCursoPorId($curso_id) {
        return Curso::findOrFail($curso_id);
    }

    public function getAlumnosPorCurso($curso_id) {
        return Alumno::where('curso_id', $curso_id)
                    ->orderBy('apellidos')
                    ->get();
    }
}