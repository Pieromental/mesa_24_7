<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComensalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('comensale');

        if ($this->isMethod('put')) {

            return [
                'nombre'    => 'required|string|max:255',
                'correo'    => 'required|email|unique:comensales,correo,' . $id,
                'telefono'  => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ];
        }
        return [
            'nombre'    => 'sometimes|required|string|max:255',
            'correo'    => 'sometimes|required|email|unique:comensales,correo,' . $id,
            'telefono'  => 'sometimes|nullable|string|max:20',
            'direccion' => 'sometimes|nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',

            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe tener un formato válido.',
            'correo.unique' => 'Este correo ya está registrado.',

            'telefono.string' => 'El teléfono debe ser una cadena.',
            'telefono.max' => 'El teléfono no debe superar los 20 caracteres.',

            'direccion.string' => 'La dirección debe ser una cadena.',
            'direccion.max' => 'La dirección no debe superar los 255 caracteres.',
        ];
    }
}
