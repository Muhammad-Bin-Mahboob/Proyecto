<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Cambia a true si quieres permitir que cualquier usuario use esta petición
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $this->product->id,
            'description' => 'nullable|string',
            'price' => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],
            'brand_id' => 'required|exists:brands,id',
            'offer_id' => 'nullable|exists:offers,id',
            'category' => 'required|string',
            'image_url' => 'nullable|image',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe un producto con ese nombre.',

            'description.string' => 'La descripción debe ser una cadena de texto.',

            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio minimo debe de ser de 1 euro.',
            'price.regex' => 'El precio debe tener como máximo dos decimales.',

            'brand_id.required' => 'La marca es obligatoria.',
            'brand_id.exists' => 'La marca seleccionada no existe.',

            'offer_id.exists' => 'La oferta seleccionada no existe.',

            'category.required' => 'La categoría es obligatoria.',
            'category.string' => 'La categoría debe ser una cadena de texto.',

            'image_url.image' => 'El archivo debe ser una imagen.',

            'is_active.boolean' => 'El estado del producto debe ser verdadero o falso.',
        ];
    }
}
