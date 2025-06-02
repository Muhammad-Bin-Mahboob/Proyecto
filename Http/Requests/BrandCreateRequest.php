<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:brands,name,',
            'image' => 'nullable|image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la marca es obligatorio.',
            'name.string' => 'El nombre de la marca debe ser una cadena de texto.',
            'name.unique' => 'Ya existe una marca con ese nombre.',

            'image.image' => 'El archivo debe ser una imagen.',
        ];
    }
}
