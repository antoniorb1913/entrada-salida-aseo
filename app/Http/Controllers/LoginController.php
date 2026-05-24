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
     * Si por algún motivo alguien llega al formulario de login 
     * (por ejemplo, al intentar entrar sin sesión), lo mandamos al Hub.
     */
    public function showLoginForm() {
        return redirect('http://localhost:8000')->with('info', 'Debes iniciar sesión en el Hub.');
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
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // --- CAMBIO CLAVE AQUÍ ---
        // En lugar de volver al login de Baños, lo mandamos al Hub
        // para que la salida sea completa.
        return redirect('http://localhost:8000'); 
    }
}