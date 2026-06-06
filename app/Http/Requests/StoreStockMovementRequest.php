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
}
