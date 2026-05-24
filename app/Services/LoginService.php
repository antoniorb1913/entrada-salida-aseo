<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    /**
     * MÉTODO: EJECUTAR LOGIN MANUAL
     * ¿Qué hace?: Recoge el usuario y la contraseña cifrada que vienen del formulario. Usa la herramienta 
     * `Auth::attempt` de Laravel para cruzarlos con la tabla de usuarios. Si no coinciden, lanza un error 
     * controlado (`LoginFailedException`). Si coinciden, refresca la sesión para que nadie pueda duplicarla y mira el 
     * rol del usuario para devolverle al controlador el nombre de la página a la que debe viajar.
     */
    public function executeLogin(array $credentials): string
    {
        // 1. Intentamos comprobar si el usuario y la contraseña existen y coinciden en la base de datos
        if (!Auth::attempt($credentials)) {
            // Si la contraseña o el usuario están mal, frena el proceso y lanza el aviso de error en texto
            throw new LoginFailedException('El nombre de usuario o la contraseña no son correctos.');
        }

        // 2. Si las credenciales son correctas, el sistema inicia la sesión y la refresca/regenera por seguridad
        request()->session()->regenerate();

        // 3. Cogemos al usuario que acaba de entrar y, según el rol de su ficha, decidimos a dónde mandarlo
        $user = Auth::user();

        if ($user->rol === 'admin') {
            return 'admin'; // Devuelve el nombre de la ruta para el Panel de Dirección
        } else {
            return 'acceso'; // Devuelve el nombre de la ruta para que los profesores elijan aula (Cambiado de 'profesor' a 'acceso' para que coincida con tus rutas)
        }
    }
}