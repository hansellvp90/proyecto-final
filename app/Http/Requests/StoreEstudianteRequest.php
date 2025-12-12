<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstudianteRequest extends FormRequest
{
    public function authorize()
    {
        // Si la API debe estar abierta a cualquiera, return true.
        // Si quieres requerir auth, validar aquí.
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:120',
            'apellido' => 'sometimes|nullable|string|max:120',
            'email'  => 'required|email|unique:estudiantes,email',
            'curso'  => 'sometimes|nullable|string|max:255',
            'fecha_nacimiento' => 'sometimes|nullable|date',
            'telefono' => 'sometimes|nullable|string|max:50',
        ];
    }
}
