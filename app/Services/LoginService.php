<?php

namespace App\Services;

use App\Exceptions\LoginFailedException;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function executeLogin(array $credentials): string
    {
        // 1. Intentamos el login
    if (!Auth::attempt($credentials)) {
        throw new LoginFailedException('El nombre o la contraseña no son correctos.');
    }

        // 2. Si tiene éxito, regeneramos sesión
        request()->session()->regenerate();

        // 3. Obtenemos al usuario y decidimos la ruta (tu lógica de ifs)
        $user = Auth::user();

        if ($user->rol === 'admin') {
            return 'admin';
        } else if ($user->rol === 'profesor') {
            return 'profesor';
        }
        return 'consulta';
    }
}