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
        $aula = Aula::create(['nombre' => 'Aula 0.9']);
        // Creamos el primer usuario (alumno) de forma manual
        Alumno::create([
            'nre'    => '793120',
            'nombre'    => 'antonio', 
            'apellidos' => 'rodriguez',
            'curso'     => '2ºDAW',
            'profesor_id'     => 1,
            'aula_id'  => $aula->id, 
        ]);
    }
}
