<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * MÉTODO SHOWLOGINFORM (Mostrar la pantalla de entrada)
     * ¿Qué hace?: Se ejecuta cuando alguien escribe la web del sistema. Primero comprueba:
     * Si el usuario YA estaba logueado de antes, en vez de volver a pedirle la contraseña, mira su rol y lo mete directo
     * a su panel (al de Dirección si es 'admin' o a los cursos si es 'profesor').
     */
    public function showLoginForm() 
    {
        // Si el usuario YA está autenticado...
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirigir según el rol
            if ($user->rol === 'admin') {
                return redirect()->route('admin');
            } elseif ($user->rol === 'profesor') {
                return redirect()->route('acceso');
            }
            
            // Si tienes más roles o un destino por defecto
            return redirect()->route('acceso');
        }

        // Si NO está autenticado, mostramos la vista del login
        return view('login'); // Asegúrate de que tu vista se llame login.blade.php
    }

    /**
     * MÉTODO LOGIN (Procesar el formulario manual)
     * ¿Qué hace?: Recoge el 'nombreUsuario' y la 'password' que se han escrito en el formulario y los pasa.
     * a LoginRequest para validarlos, luego pasa estos datos al "LoginService" para que verifique si son válidos en Postgres.
     * El servicio devuelve el nombre de la ruta a la que tiene que viajar el usuario (ej: 'admin' o 'acceso').
     */
    public function login(LoginRequest $request) {
            
        $routeName = $this->loginService->executeLogin($request->only('nombreUsuario', 'password'));

        return redirect()->route($routeName);
    }

    /**
     * MÉTODO LOGOUT (Cerrar la sesión de la aplicación)
     * ¿Qué hace?: Cuando el usuario pulsa en "Cerrar Sesión", borra sus datos de la memoria del servidor (`Auth::logout`),
     * destruye la cookie de la sesión para evitar que nadie le robe la cuenta dándole al botón "Atrás" del navegador
     * y limpia el token de seguridad.
     */
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