<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => [
                Rule::requiredIf(fn () => (int) $this->input('stock_quantity', 0) > 0),
                'nullable',
                'exists:suppliers,id',
            ],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date', 'after:today'],
            'batch_number' => [
                Rule::requiredIf(fn () => (int) $this->input('stock_quantity', 0) > 0),
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }
}
