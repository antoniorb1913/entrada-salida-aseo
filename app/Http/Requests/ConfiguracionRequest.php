<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        // puedes retornar true aquí con total seguridad.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'max_salidas'        => 'required|integer|min:1',
            'max_aforo'          => 'required|integer|min:1',
            'tiempo_espera'      => 'required|integer|min:0',
            'tiempo_cancelacion' => 'required|integer|min:0|max:30',
            'excepciones'        => 'nullable|array',
            'excepciones.*'      => 'integer|exists:alumnos,id',
        ];
    }
}