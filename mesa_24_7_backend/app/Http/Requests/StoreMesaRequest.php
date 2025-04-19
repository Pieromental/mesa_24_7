<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMesaRequest extends FormRequest
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
        return [
            'numero_mesa' => 'required|string|max:10|unique:mesas,numero_mesa',
            'capacidad'   => 'required|integer|min:1',
            'ubicacion'   => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'numero_mesa.required' => 'El número de mesa es obligatorio.',
            'numero_mesa.string'   => 'El número de mesa debe ser una cadena de texto.',
            'numero_mesa.max'      => 'El número de mesa no debe superar los 10 caracteres.',
            'numero_mesa.unique'   => 'Ya existe una mesa con ese número.',
            
            'capacidad.required'   => 'La capacidad de la mesa es obligatoria.',
            'capacidad.integer'    => 'La capacidad debe ser un número entero.',
            'capacidad.min'        => 'La capacidad debe ser al menos 1 persona.',

            'ubicacion.string'     => 'La ubicación debe ser texto.',
            'ubicacion.max'        => 'La ubicación no debe superar los 255 caracteres.',
        ];
    }
}
