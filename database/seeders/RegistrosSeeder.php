<?php

namespace Database\Seeders;

use App\Enums\Estado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 

class RegistrosSeeder extends Seeder
{
    public function run(): void
    {
        $alumnos = DB::table('alumnos')->pluck('id');
        $profesores = DB::table('profesors')->pluck('id');
        $cursos = DB::table('cursos')->pluck('id');

        $datos = [];
        for ($i = 0; $i < 50; $i++) {
            // Creamos una fecha de salida aleatoria
            $fechaSalida = Carbon::now()->subDays(rand(1, 7));
            
            // Creamos una fecha de entrada sumando entre 2 y 3 minutos
            $fechaEntrada = (clone $fechaSalida)->addMinutes(rand(2, 3));

            $datos[] = [
                'alumno_id'     => $alumnos->random(),
                'profesor_id'   => $profesores->random(),
                'curso_id'      => $cursos->random(),
                'fecha_salida'  => $fechaSalida,
                'fecha_entrada' => $fechaEntrada,
                'estado'        => Estado::EN_CLASE, 
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ];
        }

        DB::table('registros')->insert($datos);
    }
}