<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMesaRequest extends FormRequest
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
        $mesaId = $this->route('mesa'); 
      
        if ($this->isMethod('patch')) {
            return [
                'numero_mesa' => 'sometimes|string|max:10|unique:mesas,numero_mesa,' . $mesaId,
                'capacidad' => 'sometimes|integer|min:1',
                'ubicacion' => 'sometimes|string|max:255',
            ];
        }

    
        return [
            'numero_mesa' => 'required|string|max:10|unique:mesas,numero_mesa,' . $mesaId,
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_mesa.required' => 'El número de mesa es obligatorio.',
            'numero_mesa.string' => 'El número de mesa debe ser texto.',
            'numero_mesa.max' => 'El número de mesa no debe superar los 10 caracteres.',
            'numero_mesa.unique' => 'Este número de mesa ya está registrado.',

            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número.',
            'capacidad.min' => 'La capacidad debe ser al menos 1.',

            'ubicacion.string' => 'La ubicación debe ser texto.',
            'ubicacion.max' => 'La ubicación no debe superar los 255 caracteres.',
        ];
    }
}
