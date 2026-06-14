<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:entry,exit'],
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(fn () => $this->input('type') === 'entry'),
                Rule::unique('product_batches', 'number')
                    ->where(fn ($query) => $query->where('product_id', $this->input('product_id'))),
            ],
            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
                Rule::requiredIf(fn () => $this->input('type') === 'entry'),
            ],
            'expiration_date' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Selecione um produto.',
            'product_id.exists' => 'O produto selecionado não existe.',
            'quantity.required' => 'Informe a quantidade.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade deve ser pelo menos 1.',
            'type.required' => 'Informe o tipo de movimentação.',
            'type.in' => 'O tipo de movimentação selecionado é inválido.',
            'batch_number.required' => 'Informe o número do lote.',
            'batch_number.unique' => 'Este número de lote já existe para o produto selecionado.',
            'supplier_id.required' => 'Selecione um fornecedor.',
            'supplier_id.exists' => 'O fornecedor selecionado não existe.',
            'expiration_date.date' => 'Informe uma data de validade válida.',
            'expiration_date.after' => 'A validade deve ser posterior a hoje.',
        ];
    }
}
