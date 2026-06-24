<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsumidorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'numero_medidor' => [
                'required',
                'string',
                'max:100',
                Rule::unique('consumidores', 'numero_medidor')->ignore($this->route('consumidor')),
            ],
            'telefone' => 'required|string|max:20',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
