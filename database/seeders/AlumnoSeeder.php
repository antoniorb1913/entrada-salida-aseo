<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Curso; // Asegúrate de tener el modelo Curso
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AlumnoSeeder extends Seeder
{
    public function run(): void
    {
        // Faker crea datos falsos reales
        $faker = Faker::create('es_ES'); // Configuramos Faker en español

        // 1. Obtenemos todos los cursos que tienes en la base de datos (IDs del 1 al 30)
        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            // 2. Por cada curso, creamos 20 alumnos
            for ($i = 1; $i <= 20; $i++) {
                Alumno::create([
                    // Generamos un NRE aleatorio de 6-7 cifras único
                    'nre'       => $faker->unique()->numberBetween(100000, 999999),
                    'nombre'    => $faker->firstName(),
                    'apellidos' => $faker->lastName() . ' ' . $faker->lastName(),
                    'curso_id'  => $curso->id,
                    // Por defecto la excepción médica será false
                    'excepcion_limite' => false, 
                ]);
            }
        }

        // 3. (Opcional) Tus alumnos específicos para pruebas
        // Puedes dejarlos aquí abajo si quieres tener usuarios controlados
        Alumno::create([
            'nre'       => '793122',
            'nombre'    => 'Antonio', 
            'apellidos' => 'Rodríguez Test',
            'curso_id'  => 26
        ]);
    }
}