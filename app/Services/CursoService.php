<?php

namespace App\Services;

use App\Models\Curso;

class CursoService
{
    public function getEtapasUnicas() 
    {
        return Curso::distinct()->pluck('etapas');
    }
    
    // Asegúrate de que este nombre sea exacto: getNivelesPorEtapa
    public function getNivelesPorEtapa($etapa)
    {
        return Curso::where('etapas', strtoupper($etapa))
                    ->distinct()
                    ->pluck('nivel');
    }
}