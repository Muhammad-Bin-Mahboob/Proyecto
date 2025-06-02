<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Permitir a todos hacer esta solicitud (o ajusta según tu lógica)
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'required|numeric|min:1|max:100,regex:/^\d+(\.\d{1,2})?$/',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la oferta es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',

            'description.string' => 'La descripción debe ser una cadena de texto.',

            'discount.required' => 'El descuento es obligatorio.',
            'discount.numeric' => 'El descuento debe ser un número.',
            'discount.min' => 'El descuento no puede ser menor a 1.',
            'discount.max' => 'El descuento no puede ser mayor a 100.',
            'discount.regex' => 'El descuento debe tener como máximo dos decimales.',

            'is_active.boolean' => 'El campo de estado debe ser verdadero o falso.',
        ];
    }
}
