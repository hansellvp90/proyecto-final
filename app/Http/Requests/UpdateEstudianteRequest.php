<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstudianteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Obtener el ID del estudiante desde la ruta para ignorarlo en la regla unique
        $estudianteId = $this->route('estudiante');

        return [
            'nombre' => 'sometimes|required|string|max:120',
            'apellido' => 'sometimes|nullable|string|max:120',
            // Para email: unique, excepto el propio registro
            'email'  => 'sometimes|required|email|unique:estudiantes,email,' . $estudianteId,
            'curso'  => 'sometimes|nullable|string|max:255',
            'fecha_nacimiento' => 'sometimes|nullable|date',
            'telefono' => 'sometimes|nullable|string|max:50',
        ];
    }
}
