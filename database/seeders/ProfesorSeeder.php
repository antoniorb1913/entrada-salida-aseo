<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfesorSeeder extends Seeder
{
    // Este método se ejecuta cuando lanzas el comando 'php artisan db:seed'
    public function run(): void
    {
        // Creamos el primer usuario (administrador/profesor) de forma manual
        User::create([
            'nombre'    => 'Cipriano', 
            'apellidos' => 'Garcia Hermandez',
            'nombreUsuario' => 'cipriano.garcia',
            'email'     => 'cipriano.gracia@murciaeduca.es',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'),      
            'rol'       => 'profesor'
        ]);

        User::create([
            'nombre'    => 'Jefatura', 
            'apellidos' => 'Estudios',
            'nombreUsuario' => 'jefatura.estudios',
            'email'     => 'jefatura.estudios@amurciaeduca.es',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'), 
            'rol'       => 'admin'

        ]);

        User::create([
            'nombre'    => 'María Carmen', 
            'apellidos' => 'Marin Fernandez',
            'nombreUsuario' => 'mariacarmen.marin4',
            'email'     => 'mariacarmen.marin4@amurciaeduca.es',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'), 
            'rol'       => 'admin'

        ]);

        User::create([
            'nombre'    => 'Enrique', 
            'apellidos' => 'Villa Garres',
            'nombreUsuario' => 'enrique.villa',
            'email'     => 'enrique.villa@amurciaeduca.es',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'), 
            'rol'       => 'admin'

        ]);

    }
}