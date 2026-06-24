<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'consumidor_id' => 'required|integer|exists:consumidores,id',
            'mes' => 'required|digits:2',
            'ano' => 'required|digits:4|integer',
            'leitura_atual' => 'required|numeric|min:0',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
