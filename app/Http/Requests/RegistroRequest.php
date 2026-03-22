<?php


namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite que cualquier usuario (o los logueados) usen este filtro
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha'         => 'nullable|date',
            'fecha_inicio'  => 'nullable|date',
            'fecha_fin'     => 'nullable|date',
            'curso_id'      => 'nullable|exists:cursos,id',
            'profesor_id'   => 'nullable|exists:users,id',
            'alumno_id'     => 'nullable|exists:alumnos,id',
        ];
    }
    
/**
     */
    public function messages(): array
    {
        return [
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior a la de inicio.',
            'exists' => 'El elemento seleccionado no existe en nuestra base de datos.',
            'date' => 'El formato de fecha no es válido.',
        ];
    }
}
