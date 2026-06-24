<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'leitura_anterior' => 'required|numeric|min:0',
            'leitura_atual' => 'required|numeric|min:0|gt:leitura_anterior',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
