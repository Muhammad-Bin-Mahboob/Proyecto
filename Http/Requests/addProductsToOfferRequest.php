<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addProductsToOfferRequest extends FormRequest
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
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_ids.required' => 'Debes seleccionar al menos un producto.',
            'product_ids.array' => 'El formato de los productos no es válido.',
            'product_ids.*.exists' => 'Uno o más productos seleccionados no existen en la base de datos.',
        ];
    }
}
