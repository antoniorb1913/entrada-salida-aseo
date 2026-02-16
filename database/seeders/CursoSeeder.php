<?php

namespace Database\Seeders;

use App\Enums\Etapas;
use App\Models\Curso;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ESO (1º a 4º, letras A a D)
        foreach (['1º', '2º', '3º', '4º'] as $nivel) {
            foreach (['A', 'B', 'C', 'D'] as $letra) {
                Curso::create([
                    'etapas' => Etapas::ESO, 
                    'nivel'  => $nivel,
                    'letra'  => $letra,
                ]);
            }
        }
        // 2. Bachillerato (1º y 2º, letras A a D)
        foreach (['1º', '2º'] as $nivel) {
            foreach (['ARTES', 'CIENCIA', 'HUMANIDADES CCSS'] as $letra) {
                Curso::create([
                    'etapas' => Etapas::BACHILLERATO,
                    'nivel'  => $nivel,
                    'letra'  => $letra,
                ]);
            }
        }
        // 3. FP Grado Medio (SMR) - 1º y 2º
        foreach (['1º', '2º'] as $nivel) {
            Curso::create([
                'etapas' => Etapas::FP,
                'nivel'  => 'SMR',
                'letra'  => $nivel,
            ]);
        }
        // 4. FP Grado Superior (DAW) - 1º y 2º
        foreach (['1º', '2º'] as $nivel) {
            Curso::create([
                'etapas' => Etapas::FP,
                'nivel'  => 'DAW',
                'letra'  => $nivel,
            ]);
        }
        // 4. FP Grado Superior (DAW) - 1º y 2º
        foreach (['1º', '2º'] as $nivel) {
            Curso::create([
                'etapas' => Etapas::FP,
                'nivel'  => 'BASICA INFORMATICA',
                'letra'  => $nivel,
            ]);
        }
        // 4. FP Grado Superior (DAW) - 1º y 2º
        foreach (['1º', '2º'] as $nivel) {
            Curso::create([
                'etapas' => Etapas::FP,
                'nivel'  => 'BASICA VEHICULOS',
                'letra'  => $nivel,
            ]);
        }
    }
}