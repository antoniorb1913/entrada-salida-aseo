<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('Documentación/Estudios_Grupos_Final.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("Archivo no encontrado en: $filePath");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        array_shift($rows); 

        foreach ($rows as $row) {
            $descripcionRaw = $row[0];
            $grupoRaw = $row[1];
            
            $etapa = 'OTRA';
            $modalidad = null;
            $nivel = '';
            $letra = '';
        
            if (str_contains($descripcionRaw, 'Secundaria')) {
                $etapa = 'ESO';
                $nivel = mb_substr($grupoRaw, 1, 1) . 'º';
                
                $letraBase = mb_substr($grupoRaw, 2);
                $programa = str_contains($descripcionRaw, 'SB Inglés') ? '(Bilingüe)' : '(No Bilingüe)';
                
                // IMPORTANTE: Usamos el palito | para separar la letra del programa
                $letra = $letraBase . "\n" . $programa;
                
                $modalidad = null;
            }
            elseif (str_contains($descripcionRaw, 'Bachillerato')) {
                $etapa = 'BACHILLERATO';
                $modalidad = $descripcionRaw; 
                $nivel = mb_substr($grupoRaw, 1, 1) . 'º';
                $letra = mb_substr($grupoRaw, 2);
            } 
            else {
                $etapa = 'FP';
                $modalidad = $descripcionRaw; 
                $nivel = mb_substr($grupoRaw, -1) . 'º';
                $letra = mb_substr($grupoRaw, 0, -1);
            }
        
            Curso::create([
                'etapas'    => $etapa,
                'modalidad' => $modalidad,
                'nivel'     => $nivel,
                'letra'     => $letra ?: 'A',
            ]);
        }
        
        $this->command->info('Cursos importados correctamente.');
    }
}