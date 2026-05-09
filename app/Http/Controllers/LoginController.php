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
    public function login(LoginRequest $request) {
            
            $routeName = $this->loginService->executeLogin($request->only('nombreUsuario', 'password'));

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