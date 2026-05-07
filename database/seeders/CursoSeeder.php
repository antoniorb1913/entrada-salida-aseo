<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        // Asegúrate de que la ruta sea correcta según tu sistema (Windows usa \ y Linux/Mac /)
        $filePath = base_path('Documentación/Estudios_Grupos_Final.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("Archivo no encontrado en: $filePath");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        array_shift($rows); // Quitar cabeceras

        foreach ($rows as $row) {
            $descripcionRaw = $row[0]; // descrip. ensenanza (ej. Bachillerato de Ciencias...)
            $grupoRaw = $row[1];       // grupo (ej. B1A)
            
            $etapa = 'OTRA';
            $modalidad = null;
            $nivel = '';
            $letra = '';

            // 1. Clasificación por Etapa y Modalidad
            if (str_contains($descripcionRaw, 'Secundaria')) {
                $etapa = 'ESO';
                // Modalidades específicas de la ESO
                if (str_contains($descripcionRaw, 'Programa de Mejora')) {
                    $modalidad = 'Programa de Mejora en Lenguas Extranjeras';
                } elseif (str_contains($descripcionRaw, 'SB Inglés')) {
                    $modalidad = 'SB Inglés';
                }
            } 
            elseif (str_contains($descripcionRaw, 'Bachillerato')) {
                $etapa = 'BACHILLERATO';
                // ASIGNAMOS LA MODALIDAD DIRECTAMENTE DEL EXCEL
                $modalidad = $descripcionRaw; 
            } 
            elseif (str_contains($descripcionRaw, 'Informática') || str_contains($descripcionRaw, 'Sistemas') || str_contains($descripcionRaw, 'Desarrollo') || str_contains($descripcionRaw, 'Mantenimiento')) {
                $etapa = 'FP';
                $modalidad = $descripcionRaw; 
            }

            // 2. Lógica de Extracción de Nivel y Letra
            if ($etapa === 'ESO' || $etapa === 'BACHILLERATO') {
                // Para B1A: nivel es '1', letra es 'A'
                $nivel = mb_substr($grupoRaw, 1, 1) . 'º'; 
                $letra = mb_substr($grupoRaw, 2);          
            } else {
                // Para FP (DAW1): nivel es el último carácter
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
        
        $this->command->info('Cursos importados correctamente con modalidades divididas.');
    }
}