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
            'nombre'    => 'Cipriano ', 
            'apellidos' => 'garcia hermandez',
            'email'     => 'cipriano.gracia@murciaeduca.es',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'),      
            'rol'       => 'profesor'
        ]);

        User::create([
            'nombre'    => 'admin', 
            'apellidos' => 'admin',
            'email'     => 'admin@admin.com',
            
            // Hash::make encripta la contraseña. ¡Nunca guardes claves en texto plano!
            'password'  => Hash::make('1234'), 
            'rol'       => 'admin'

        ]);

    }
}