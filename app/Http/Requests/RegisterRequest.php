<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'O nome é obrigatório.',
            'email.required'           => 'O email é obrigatório.',
            'email.unique'             => 'Este email já está registrado.',
            'password.required'        => 'A palavra-passe é obrigatória.',
            'password.min'             => 'A palavra-passe deve ter no mínimo 8 caracteres.',
            'password.confirmed'       => 'As palavras-passe não coincidem.',
        ];
    }
}