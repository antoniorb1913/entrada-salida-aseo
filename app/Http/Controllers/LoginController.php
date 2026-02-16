<?php

namespace App\Http\Controllers;

use App\Exceptions\LoginFailedException;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
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

    public function login(LoginRequest $request) {
            
            $routeName = $this->loginService->executeLogin($request->only('nombre', 'password'));

            return redirect()->route($routeName);

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