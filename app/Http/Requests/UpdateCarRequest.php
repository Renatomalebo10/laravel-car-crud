<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:255',
            'ano'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placa'       => [
                'required',
                'string',
                'max:20',
                'unique:cars,placa,' . $this->route('car')->id,
                'regex:/^[A-Z]{2,3}-\d{2}-\d{2}-[A-Z]{1,2}$/i',
            ],
            'preco'  => 'required|numeric|min:0',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'A categoria é obrigatória.',
            'placa.required'       => 'A placa do veículo é obrigatória.',
            'placa.unique'         => 'Esta placa já pertence a outro veículo.',
            'placa.regex'          => 'Formato de placa inválido (Ex: LD-12-34-AB).',
            'imagem.max'           => 'A imagem não pode exceder 2MB.',
        ];
    }
}