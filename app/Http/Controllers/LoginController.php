<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        // 1. Comprobamos que el usuario haya rellenado los campos necesarios
        $request->validate([
            'nombre' => 'required',
            'password' => 'required',
        ]);

        // 2. Guardamos los datos recibidos para intentar la conexión
        $credentials = [
            'nombre'   => $request->nombre,
            'password' => $request->password,
        ];

        // 3. Verificamos si los datos coinciden con la base de datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        
            // Obtenemos al usuario que acaba de entrar
            $user = Auth::user();
            // Comprobamos el campo rol es admin
            if ($user->rol === 'admin') {
                // Coincide con ->name('admin') en web.php
                return redirect()->route('admin');

            } else if ($user->rol === 'profesor') {
                // Coincide con ->name('profesor') en web.php
                return redirect()->route('profesor');
            }
            // Coincide con ->name('consulta') en web.php
            return redirect()->route('consulta');
        }

        // 4. Si los datos están mal, volvemos atrás con un mensaje de error
        return back()->withErrors(['nombre' => 'El nombre o la contraseña no son correctos.']);
    }

    public function logout(Request $request) {
        // 1. Cerramos la sesión del usuario
        Auth::logout();
        
        // 2. Limpiamos y refrescamos la sesión para que sea segura
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // 3. Enviamos al usuario de vuelta al formulario de login
        return redirect()->route('login');
    }
}