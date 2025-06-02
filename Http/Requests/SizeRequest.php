<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // O ajusta según tu lógica de permisos
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'size' => 'required|integer|between:30,60',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El ID del producto es obligatorio.',
            'product_id.exists' => 'El producto seleccionado no existe.',

            'size.required' => 'La talla es obligatoria.',
            'size.integer' => 'La talla debe ser un número entero.',
            'size.between' => 'La talla debe estar entre 30 y 60.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
        ];
    }
}

