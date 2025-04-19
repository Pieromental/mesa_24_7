<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservaRequest extends FormRequest
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
            'fecha.date' => 'La fecha debe ser válida.',
            'fecha.after_or_equal' => 'No puedes reservar para fechas pasadas.',

            'hora.required' => 'La hora es obligatoria.',
            'hora.date_format' => 'El formato de la hora debe ser HH:MM (24 horas).',

            'numero_de_personas.required' => 'Indica cuántas personas asistirán.',
            'numero_de_personas.integer' => 'El número debe ser un valor entero.',
            'numero_de_personas.min' => 'Debe asistir al menos una persona.',

            'comensal_id.required' => 'Debes seleccionar un comensal.',
            'comensal_id.exists' => 'El comensal seleccionado no existe.',

            'mesa_id.required' => 'Debes seleccionar una mesa.',
            'mesa_id.exists' => 'La mesa seleccionada no existe.',
        ];
    }
}
