<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite a cualquier usuario hacer la petición
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El comentario no esta vinculado con ningun producto.',
            'product_id.exists' => 'El producto seleccionado no existe.',

            'comment.required' => 'El comentario es obligatorio.',
            'comment.string' => 'El comentario debe ser una cadena de texto.',
            'comment.max' => 'El comentario no puede tener más de 1000 caracteres.',
        ];
    }
}


