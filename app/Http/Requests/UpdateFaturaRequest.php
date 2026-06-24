<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pendente,pago',
            'data_vencimento' => 'nullable|date',
        ];
    }

    public function validatedData(): array
    {
        return $this->validated();
    }
}
