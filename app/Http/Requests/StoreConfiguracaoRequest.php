<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfiguracaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'taxa_fixa' => 'required|numeric|min:0',
            'valor_excedente' => 'required|numeric|min:0',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
