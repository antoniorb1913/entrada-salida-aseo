<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Alumno;
use App\Models\Aula;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    // Este método se ejecuta cuando lanzas el comando 'php artisan db:seed'
    public function run(): void
    {
        Alumno::create([
            'nre'    => '793120',
            'nombre'    => 'antonio', 
            'apellidos' => 'rodriguez',
            'curso_id'     => 26
            
        ]);
        Alumno::create([
            'nre'    => '793120',
            'nombre'    => 'Paco', 
            'apellidos' => 'rodriguez',
            'curso_id'     => 19
        ]);
        Alumno::create([
            'nre'    => '793120',
            'nombre'    => 'Juan', 
            'apellidos' => 'rodriguez',
            'curso_id'     => 13
        ]);
        Alumno::create([
            'nre'    => '793120',
            'nombre'    => 'antonio', 
            'apellidos' => 'rodriguez',
            'curso_id'     => 2
        ]);

    }
}
