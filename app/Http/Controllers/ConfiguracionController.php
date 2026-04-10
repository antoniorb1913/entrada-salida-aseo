<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;

class ConfiguracionController extends Controller
{
    public function index()
    {
        // Rescatamos los valores o ponemos los de por defecto
        $maxSalidas = DB::table('configuraciones')->where('clave', 'max_salidas')->value('valor') ?? 3;
        $tiempoEsperaSegundos = DB::table('configuraciones')->where('clave', 'tiempo_espera_segundos')->value('valor') ?? 300;
        
        // Convertimos los segundos a minutos para que el admin lo entienda mejor
        $tiempoEsperaMinutos = $tiempoEsperaSegundos / 60;

        $alumnos = Alumno::with('curso')->orderBy('curso_id')->orderBy('apellidos')->get();

        return view('configuracion', compact('maxSalidas', 'tiempoEsperaMinutos', 'alumnos'));
    }

    public function guardar(Request $request)
    {
        // 1. Guardar salidas
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'max_salidas'],
            ['valor' => $request->max_salidas, 'updated_at' => now()]
        );

        // 2. Guardar tiempo (multiplicamos los minutos del formulario por 60 para guardar segundos)
        DB::table('configuraciones')->updateOrInsert(
            ['clave' => 'tiempo_espera_segundos'],
            ['valor' => $request->tiempo_espera * 60, 'updated_at' => now()]
        );

        // 3. Reiniciar excepciones y aplicar las nuevas
        Alumno::query()->update(['excepcion_limite' => false]);

        if ($request->has('excepciones')) {
            Alumno::whereIn('id', $request->excepciones)->update(['excepcion_limite' => true]);
        }

        return redirect()->route('configuracion.index')->with('success', 'Configuración guardada correctamente.');
    }
}