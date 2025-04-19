<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
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
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'numero_de_personas' => 'required|integer|min:1',
            'comensal_id' => 'required|exists:comensales,id',
            'mesa_id' => 'required|exists:mesas,id',
        ];

    }
    
    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha de la reserva es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido.',
            'fecha.after_or_equal' => 'La fecha no puede ser anterior al día de hoy.',

            'hora.required' => 'La hora de la reserva es obligatoria.',
            'hora.date_format' => 'La hora debe tener el formato HH:MM.',

            'numero_de_personas.required' => 'Debes indicar cuántas personas asistirán.',
            'numero_de_personas.integer' => 'El número de personas debe ser un número entero.',
            'numero_de_personas.min' => 'Debe haber al menos una persona.',

            'comensal_id.required' => 'Debes seleccionar un comensal.',
            'comensal_id.exists' => 'El comensal no existe en el sistema.',

            'mesa_id.required' => 'Debes seleccionar una mesa.',
            'mesa_id.exists' => 'La mesa seleccionada no existe.',
        ];
    }
}
