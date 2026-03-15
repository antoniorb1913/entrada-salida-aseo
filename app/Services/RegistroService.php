<?php

namespace App\Services;

use App\Enums\Estado;
use App\Models\Alumno;
use App\Models\Registro;
use Carbon\Carbon;

class RegistroService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function registrar_salida_alumno($alumno_id, $profesor_id)
    {
        // Buscamos al alumno para saber a qué curso pertenece
        $alumno = Alumno::findOrFail($alumno_id);

        return Registro::create([
            'alumno_id'    => $alumno_id,
            'profesor_id'  => $profesor_id,
            'curso_id'     => $alumno->curso_id, // <--- Guardamos el curso automáticamente
            'fecha_salida' => now(),
            'estado'       => Estado::FUERA 
        ]);
    }

    public function registrar_entrada_alumno($alumno_id)
    {
        // Buscamos el registro activo (el que no tiene fecha_entrada aún)
        $registro = Registro::where('alumno_id', $alumno_id)
                            ->where('estado', Estado::FUERA)
                            ->latest()
                            ->first();

        if ($registro) {
            $registro->update([
                'fecha_entrada' => now(), // Guarda fecha y hora de la vuelta
                'estado'        => Estado::EN_CLASE 
            ]);
        }
    }
}
