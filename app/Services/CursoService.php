<?php

namespace App\Services;

use App\Models\Curso;

class CursoService
{

    public function getEtapasUnicas() {
        return Curso::distinct()->pluck('etapas');
    }
    
    public function getNivelesPorEtapa($etapa)
    {
        // Usamos whereRaw para que busque sin importar si es ESO o eso
        return Curso::whereRaw('UPPER(etapas) = ?', [strtoupper($etapa)])
                    ->distinct()
                    ->pluck('nivel');
    }
}
