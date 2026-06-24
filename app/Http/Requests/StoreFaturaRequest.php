<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'consumidor_id' => 'required|integer|exists:consumidores,id',
            'leitura_id' => 'nullable|integer|exists:leituras,id',
            'mes' => 'nullable|digits:2',
            'ano' => 'nullable|digits:4',
            'status' => 'required|in:pendente,pago',
            'data_vencimento' => 'nullable|date',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
