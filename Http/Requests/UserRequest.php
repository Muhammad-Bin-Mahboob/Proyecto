<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'rol' => 'required|in:client,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido. Debe ser "client" o "admin".',
        ];
    }
}
