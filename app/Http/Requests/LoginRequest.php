<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'O email é obrigatório.',
            'email.email'       => 'O formato do email é inválido.',
            'password.required' => 'A palavra-passe é obrigatória.',
        ];
    }
}