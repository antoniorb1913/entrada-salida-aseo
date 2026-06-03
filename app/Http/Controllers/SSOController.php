<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SSOController extends Controller
{
    public function loginViaToken(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'No se recibió el token de acceso.');
        }

        try {
            $data = Crypt::decrypt($token);

            if (now()->timestamp - $data['time'] > 60) {
                return redirect()->route('login')->with('error', 'El token de sesión ha expirado.');
            }

            $fullName = $data['name'] ?? 'Usuario';
            $parts = explode(' ', trim($fullName));
            
            $nombre = count($parts) > 0 ? array_shift($parts) : $fullName;
            $apellidos = count($parts) > 0 ? implode(' ', $parts) : '';

            $user = User::where('email', $data['email'])->first();
            
            // --- LÓGICA DE SEGURIDAD PARA EL ROL ---
            $rolGlobal = strtolower($data['rol_global'] ?? ($data['rol'] ?? 'profesor'));
            $rolModulo = strtolower($data['rol_modulo'] ?? 'profesor');

            // Regla principal: el rol que manda dentro de esta app es el rol del módulo
            $rolRecibido = $rolModulo;

            // Si el hub envía superadmin a nivel global, aquí entra como admin
            if ($rolGlobal === 'superadmin') {
                $rolRecibido = 'admin';
            }

            // Compatibilidad defensiva
            if ($rolRecibido === 'superadmin') {
                $rolRecibido = 'admin';
            }

            if ($rolRecibido === 'usuario') {
                $rolRecibido = 'profesor';
            }

            if (!$user) {
                // Sacamos el texto anterior a la '@' del email para usarlo como nombreUsuario (ej: 793120)
                $nombreUsuarioPropuesto = head(explode('@', $data['email']));
    
                $user = User::create([
                    'nombre'        => $nombre,
                    'apellidos'     => $apellidos, 
                    'nombreUsuario' => $nombreUsuarioPropuesto,
                    'email'         => $data['email'],
                    'password'      => bcrypt(str()->random(16)),
                    'rol'           => $rolRecibido 
                ]);
            } else {
                $rolFinal = ($user->rol === 'admin') ? 'admin' : $rolRecibido;                
                
                $user->update([
                    'nombre'    => $nombre,
                    'apellidos' => $apellidos,
                    'rol'       => $rolFinal
                    // Si en el update también te chillara por el nombreUsuario, puedes añadirlo aquí abajo:
                    // 'nombreUsuario' => head(explode('@', $data['email'])),
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->put('sso_login', true);

            if ($user->rol === 'admin') {
                return redirect()->route('admin'); // Va al Dashboard de Dirección
            }

            return redirect()->route('acceso'); // El Profesor va directo a la selección de aulas/baños

        } catch (\Exception $e) {
            // Volvemos a activar el log normal ahora que está solucionado
            Log::error('Error en SSO: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error en la comunicación SSO: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->away('http://happs.cgarcher.dev/logout-total');
    }
}