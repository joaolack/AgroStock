<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WriteOffExpiredBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Informe a quantidade para baixa.',
            'quantity.integer' => 'A quantidade para baixa deve ser um número inteiro.',
            'quantity.min' => 'A quantidade para baixa deve ser pelo menos 1.',
        ];
    }
}
