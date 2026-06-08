<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Curso; 
use App\Enums\Genero; // Asegúrate de que tu Enum está en esta ruta
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // Faker crea datos falsos reales configurado en español
        $faker = Faker::create('es_ES'); 

        // 1. Obtenemos todos los cursos de la base de datos
        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            // 2. Por cada curso, creamos 20 alumnos de forma realista
            for ($i = 1; $i <= 20; $i++) {
                
                // Decidimos el género al azar (50% de probabilidad)
                $generoAleatorio = $faker->randomElement(['MASCULINO', 'FEMENINO']);
                
                // Generamos el nombre acorde al género para que coincida
                if ($generoAleatorio === 'MASCULINO') {
                    $nombre = $faker->firstNameMale();
                    $generoEnum = Genero::MASCULINO;
                } else {
                    $nombre = $faker->firstNameFemale();
                    $generoEnum = Genero::FEMENINO;
                }

                Alumno::create([
                    'nre'              => $faker->unique()->numberBetween(100000, 999999),
                    'nombre'           => $nombre,
                    'apellidos'        => $faker->lastName() . ' ' . $faker->lastName(),
                    'genero'           => $generoEnum, 
                    'curso_id'         => $curso->id,
                    'excepcion_limite' => false, 
                    'necesita_tutor'   => false,
                ]);
            }
        }

        // 3. Alumno específico para tus pruebas controladas
        Alumno::create([
            'nre'              => '793122',
            'nombre'           => 'Antonio', 
            'apellidos'        => 'Rodríguez Test',
            'genero'           => Genero::MASCULINO, 
            'curso_id'         => 48,
            'excepcion_limite' => true,
            'necesita_tutor'   => false,
        ]);
    }
}