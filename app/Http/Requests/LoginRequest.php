<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombreUsuario' => 'required|string',
            'password' => 'required'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException
            (ApiResponse::error($validator->errors(),'Errores validaciones', 422));
    }
}