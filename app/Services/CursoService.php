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
    public function getModalidadesPorEtapa($etapa) {
        return Curso::where('etapas', $etapa)
                    ->distinct()
                    ->pluck('modalidad');
    }
    
    public function getNivelesPorEtapa($etapa, $modalidad) {
        $query = Curso::where('etapas', $etapa);
        
        // Si la modalidad es 'comun' o null, filtramos adecuadamente
        if ($modalidad !== 'comun') {
            $query->where('modalidad', $modalidad);
        }
        
        return $query->distinct()->pluck('nivel');
    }
    // CursoService.php

    public function getLetrasPorNivel($etapa, $modalidad, $nivel) {
        $query = Curso::where('etapas', strtoupper($etapa))
                    ->where('nivel', $nivel);

        // Si la modalidad es 'comun', buscamos donde sea null
        if ($modalidad === 'comun') {
            $query->whereNull('modalidad');
        } else {
            $query->where('modalidad', $modalidad);
        }

        return $query->get(['id', 'letra']); 
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